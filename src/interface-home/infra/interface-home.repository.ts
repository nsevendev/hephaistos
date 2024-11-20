import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { InterfaceHome } from '../domaine/interface-home.entity'

@Injectable()
export class InterfaceHomeRepository {
    constructor(
        @InjectRepository(InterfaceHome)
        public readonly repository: Repository<InterfaceHome>
    ) {}
}
