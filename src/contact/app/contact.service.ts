import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { ContactRepository } from '../infra/contact.repository'
import { CreateContactDto } from './create-contact.dto'
import { UpdateContactDto } from './update-contact.dto'
import { ServiceRepository } from '../../service/infra/service.repository'
import { MechanicalServiceRepository } from '../../mechanical-service/infra/mechanical-service.repository'
import { In } from 'typeorm'

@Injectable()
export class ContactService {
    constructor(
        private readonly contactRepository: ContactRepository,
        private readonly serviceRepository: ServiceRepository,
        private readonly mechanicalServiceRepository: MechanicalServiceRepository
    ) {}

    createContact = async (createContactDto: CreateContactDto) => {
        const { service_id, mechanical_service_id, ...rest } = createContactDto

        const service = await this.serviceRepository.repository.findOne({ where: { id: service_id } })
        if (!service) {
            throw new BadRequestException(`Le service avec l'ID ${service_id} n'existe pas.`)
        }

        let mechanicalService = null
        if (mechanical_service_id) {
            mechanicalService = await this.mechanicalServiceRepository.repository.findOne({
                where: { id: mechanical_service_id },
            })
            if (!mechanicalService) {
                throw new BadRequestException(
                    `Le service mécanique avec l'ID ${mechanical_service_id} n'existe pas.`
                )
            }
        }

        const newContact = this.contactRepository.repository.create({
            ...rest,
            service,
            mechanical_service: mechanicalService,
        })

        return await this.contactRepository.repository.save(newContact)
    }

    getContact = async (ids: number[] = []) => {
        if (ids.length > 0) {
            return await this.contactRepository.repository.find({
                where: { id: In(ids) },
                relations: ['service', 'mechanical_service'],
            })
        } else {
            return await this.contactRepository.repository.find({
                relations: ['service', 'mechanical_service'],
            })
        }
    }

    updateContact = async (id: number, updateContactDto: UpdateContactDto) => {
        const existingContact = await this.contactRepository.repository.findOne({
            where: { id },
            relations: ['service', 'mechanical_service'],
        })

        if (!existingContact) {
            throw new NotFoundException(`Le contact avec l'ID ${id} n'existe pas.`)
        }

        Object.assign(existingContact, updateContactDto)

        if (updateContactDto.service_id) {
            const service = await this.serviceRepository.repository.findOne({
                where: { id: updateContactDto.service_id },
            })
            if (!service) {
                throw new BadRequestException(
                    `Le service avec l'ID ${updateContactDto.service_id} n'existe pas.`
                )
            }
            existingContact.service = service
        }

        if (updateContactDto.mechanical_service_id) {
            const mechanicalService = await this.mechanicalServiceRepository.repository.findOne({
                where: { id: updateContactDto.mechanical_service_id },
            })
            if (!mechanicalService) {
                throw new BadRequestException(
                    `Le service mécanique avec l'ID ${updateContactDto.mechanical_service_id} n'existe pas.`
                )
            }
            existingContact.mechanical_service = mechanicalService
        }

        await this.contactRepository.repository.save(existingContact)

        return existingContact
    }

    deleteContact = async (ids: number[]) => {
        if (ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }

        const contactsToDelete = await this.contactRepository.repository.findByIds(ids)
        if (contactsToDelete.length === 0) {
            throw new NotFoundException(`Aucun contact correspondant aux IDs fournis.`)
        }

        const deletedContacts = await this.contactRepository.repository.remove(contactsToDelete)
        return { deletedCount: deletedContacts.length }
    }
}
