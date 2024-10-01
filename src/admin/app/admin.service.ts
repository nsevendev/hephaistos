import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { AdminRepository } from '../infra/admin.repository'

@Injectable()
export class AdminService extends BaseService {
    constructor(private readonly adminRepository: AdminRepository) {
        super('AdminService')
    }

    getAdmins = async () => {
        return await this.adminRepository.repository.find()
    }

    createAdmin = () => {
        return this.adminRepository.repository.create()
    }
}
