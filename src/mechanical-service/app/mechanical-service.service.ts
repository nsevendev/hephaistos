import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { MechanicalServiceRepository } from '../infra/mechanical-service.repository'
import { CreateMechanicalServiceDto } from './create-mechanical-service.dto'
import { UpdateMechanicalServiceDto } from './update-mechanical-service.dto'
import { UserService } from '../../user/app/user.service'
import { In, Like } from 'typeorm'

@Injectable()
export class MechanicalServiceService {
    constructor(
        private readonly mechanicalServiceRepository: MechanicalServiceRepository,
        private readonly userService: UserService
    ) {}

    createMechanicalService = async (createMechanicalServiceDto: CreateMechanicalServiceDto) => {
        const { name, lower_price, created_by } = createMechanicalServiceDto

        const creator = await this.userService.getUsers([created_by])[0]
        if (!creator) {
            throw new BadRequestException("L'utilisateur spécifié est introuvable.")
        }

        const newService = this.mechanicalServiceRepository.repository.create({
            name,
            lower_price,
            created_by: creator,
        })

        return await this.mechanicalServiceRepository.repository.save(newService)
    }

    getMechanicalService = async (ids: number[] = []) => {
        if (ids.length > 0) {
            return await this.mechanicalServiceRepository.repository.find({
                where: { id: In(ids) },
                relations: ['created_by'],
            })
        } else {
            return await this.mechanicalServiceRepository.repository.find({
                relations: ['created_by'],
            })
        }
    }

    getMechanicalServiceByFilter = async (nameToSearch: string) => {
        return await this.mechanicalServiceRepository.repository.find({
            where: { name: Like(`%${nameToSearch}%`) },
            relations: ['created_by'],
        })
    }

    updateMechanicalService = async (id: number, updatedData: UpdateMechanicalServiceDto) => {
        const existingService = await this.mechanicalServiceRepository.repository.findOne({
            where: { id },
            relations: ['created_by'],
        })

        if (!existingService) {
            throw new NotFoundException(`Le service mécanique avec l'ID ${id} n'existe pas.`)
        }

        const updatedService = {
            ...existingService,
            ...updatedData,
            created_by: existingService.created_by,
        }

        return await this.mechanicalServiceRepository.repository.save(updatedService)
    }

    deleteMechanicalService = async (id: number) => {
        const service = await this.mechanicalServiceRepository.repository.findOne({
            where: { id },
            relations: ['created_by'],
        })

        if (!service) {
            throw new NotFoundException(`Le service mécanique avec l'ID ${id} n'existe pas.`)
        }

        await this.mechanicalServiceRepository.repository.remove(service)
    }
}
