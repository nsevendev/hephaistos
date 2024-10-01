import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Admin } from '../domaine/admin.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class AdminRepository {
    constructor(
        @InjectRepository(Admin)
        public repository: Repository<Admin>
    ) {}
}
