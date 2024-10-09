import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Service } from '../domaine/service.entity'

@Injectable()
export class ServiceRepository {
    constructor(
        @InjectRepository(Service)
        public readonly repository: Repository<Service>
    ) {}
}
