import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Role } from '../domaine/role.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class RoleRepository {
    constructor(
        @InjectRepository(Role)
        public repository: Repository<Role>
    ) {}
}
