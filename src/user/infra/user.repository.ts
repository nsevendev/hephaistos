import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { User } from '../domaine/user.entity'

@Injectable()
export class UserRepository {
    constructor(
        @InjectRepository(User)
        public readonly repository: Repository<User>
    ) {}
}
