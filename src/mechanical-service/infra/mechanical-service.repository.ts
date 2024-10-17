import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { MechanicalService } from '../domaine/mechanical-service.entity'

@Injectable()
export class MechanicalServiceRepository {
    constructor(
        @InjectRepository(MechanicalService)
        public readonly repository: Repository<MechanicalService>
    ) {}
}
