import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common'
import { ServiceRepository } from '../infra/service.repository'
import { CreateServiceDto } from './create-service.dto'
import { In } from 'typeorm'
import { UserService } from '../../user/app/user.service'

@Injectable()
export class ServiceService {
    constructor(
        private readonly serviceRepository: ServiceRepository,
        private readonly userService: UserService
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

        return services
    }

    createService = async (createServiceDto: CreateServiceDto) => {
        const { name, created_by } = createServiceDto

        const user = await this.userService.getUsers([created_by])
        if (!user[0]) {
            throw new BadRequestException('L utilisateur spécifié est introuvable.')
        }

        return this.serviceRepository.createAndSave({
            name,
            created_by: user[0],
        })
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

    deleteService = async (serviceIds: number[]) => {
        const result = await this.serviceRepository.repository.delete(serviceIds)

        if (result.affected === 0) {
            throw new NotFoundException(`Aucun service trouvé pour les ID fournis.`)
        }

        const message = {
            message: `${result.affected} service(s) supprimée`,
            deleteCount: result.affected,
        }

        return message
    }
}
