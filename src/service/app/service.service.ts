import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { ServiceRepository } from '../infra/service.repository'

@Injectable()
export class ServiceService extends BaseService {
    constructor(private readonly serviceRepository: ServiceRepository) {
        super('ServiceService')
    }

    getServices = async () => {
        return await this.serviceRepository.repository.find()
    }

    createService = () => {
        return this.serviceRepository.repository.create()
    }
}
