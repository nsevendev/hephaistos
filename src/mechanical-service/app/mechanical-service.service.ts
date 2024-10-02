import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { MechanicalServiceRepository } from '../infra/mechanical-service.repository'

@Injectable()
export class MechanicalServiceService extends BaseService {
    constructor(private readonly mechanicalServiceRepository: MechanicalServiceRepository) {
        super('MechanicalServiceService')
    }

    getMechanicalServices = async () => {
        return await this.mechanicalServiceRepository.repository.find()
    }

    createMechanicalService = () => {
        return this.mechanicalServiceRepository.repository.create()
    }
}
