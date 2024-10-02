import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { MechanicalService } from '../domaine/mechanical-service.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class MechanicalServiceRepository {
    constructor(
        @InjectRepository(MechanicalService)
        public repository: Repository<MechanicalService>
    ) {}
}
