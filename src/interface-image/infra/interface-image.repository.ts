import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { InterfaceImage } from '../domaine/interface-image.entity'

@Injectable()
export class InterfaceImageRepository {
    constructor(
        @InjectRepository(InterfaceImage)
        public readonly repository: Repository<InterfaceImage>
    ) {}
}
