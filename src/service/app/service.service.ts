import { Injectable, NotFoundException } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { ServiceRepository } from '../infra/service.repository'
import { Service } from '../domaine/service.entity'
import { AdminRepository } from '../../admin/infra/admin.repository'

@Injectable()
export class ServiceService extends BaseService {
    constructor(
        private readonly serviceRepository: ServiceRepository,
        private readonly adminRepository: AdminRepository
    ) {
        super('ServiceService')
    }

    async getServices(): Promise<Service[]> {
        return await this.serviceRepository.repository.find()
    }

    async createService(data: { name: string; createdBy: number }): Promise<Service> {
        if (!data.name || data.name.trim() === '') {
            throw new Error('Le champ name est requis.')
        }

        const adminExists = await this.adminRepository.repository.findOne({ where: { id: data.createdBy } })
        if (!adminExists) {
            throw new NotFoundException(`L'admin avec l'ID ${data.createdBy} n'existe pas.`)
        }

        const newService = this.serviceRepository.repository.create({
            name: data.name,
            created_by: { id: data.createdBy },
        })

        return await this.serviceRepository.repository.save(newService)
    }

    async deleteService(id: number): Promise<void> {
        const service = await this.serviceRepository.repository.findOne({ where: { id } })

        if (!service) {
            throw new Error(`Le service avec l'ID ${id} n'a pas été trouvé.`)
        }

        await this.serviceRepository.repository.remove(service)
    }
}
