import { Injectable } from '@nestjs/common'
import { UserRepository } from '../../user/infra/user.repository'
import { RoleService } from '../../role/app/role.service'

@Injectable()
export class UserService {
    constructor(
        private readonly userRepository: UserRepository,
        private readonly roleservice: RoleService
    ) {}
}
