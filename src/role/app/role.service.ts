import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { RoleRepository } from '../infra/role.repository'

@Injectable()
export class RoleService extends BaseService {
    constructor(private readonly roleRepository: RoleRepository) {
        super('RoleService')
    }

    getRoles = async () => {
        return await this.roleRepository.repository.find()
    }

    createRole = () => {
        return this.roleRepository.repository.create()
    }
}
