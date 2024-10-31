import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { CarForSaleImage } from '../domaine/car-for-sale-image.entity'

@Injectable()
export class CarForSaleImageRepository {
    constructor(
        @InjectRepository(CarForSaleImage)
        public readonly repository: Repository<CarForSaleImage>
    ) {}
}
