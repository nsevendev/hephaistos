import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Role } from '../domaine/role.entity'

@Injectable()
export class RoleRepository {
    constructor(
        @InjectRepository(Role)
        public readonly repository: Repository<Role>
    ) {}

    async findOneById(id: number): Promise<Role | undefined> {
        return this.repository.findOne({ where: { id } })
    }
}
