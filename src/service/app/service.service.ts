import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common'
import { ServiceRepository } from '../infra/service.repository'
import { UserRepository } from '../../user/infra/user.repository'
import { CreateServiceDto } from './create-service.dto'
import { In } from 'typeorm'

@Injectable()
export class ServiceService {
    constructor(
        private readonly serviceRepository: ServiceRepository,
        private readonly userRepository: UserRepository
    ) {}

    getServices = async (ids: number[] = []) => {
        if (!Array.isArray(ids) || !ids.every((id) => typeof id === 'number')) {
            throw new BadRequestException('Les IDs fournis doivent être un tableau de nombres.')
        }

        if (ids.length === 0) {
            return await this.serviceRepository.repository.find({ relations: ['created_by'] })
        }

        const services = await this.serviceRepository.repository.find({
            where: { id: In(ids) },
            relations: ['created_by'],
        })

        const notFoundIds = ids.filter((id) => !services.some((service) => service.id === id))
        if (notFoundIds.length > 0) {
            throw new NotFoundException(
                `Les services avec les IDs suivants sont introuvables : ${notFoundIds.join(', ')}.`
            )
        }

        return services
    }

    createService = async (createServiceDto: CreateServiceDto) => {
        const { name, created_by } = createServiceDto

        const user = await this.userRepository.repository.findOne({ where: { id: created_by } })
        if (!user) {
            throw new BadRequestException('L utilisateur spécifié est introuvable.')
        }

        const newService = this.serviceRepository.repository.create({
            name,
            created_by: user,
        })

        return await this.serviceRepository.repository.save(newService)
    }

    updateService = async (id: number, updateServiceName: string) => {
        if (typeof updateServiceName !== 'string' || updateServiceName.trim() === '') {
            throw new BadRequestException('Le nom du service doit être une chaîne de caractères non vide.')
        }

        const service = await this.serviceRepository.repository.findOne({
            where: { id },
            relations: ['created_by'],
        })

        if (!service) {
            throw new NotFoundException(`Le service avec l'ID ${id} est introuvable.`)
        }

        service.name = updateServiceName.trim()

        return await this.serviceRepository.repository.save(service)
    }

    deleteService = async (id: number[]) => {
        const service = await this.getServices(id)
        await this.serviceRepository.repository.remove(service)
    }
}
