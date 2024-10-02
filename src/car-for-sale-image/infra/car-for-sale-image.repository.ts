import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { CarForSaleImage } from '../domaine/car-for-sale-image.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class CarForSaleImageRepository {
    constructor(
        @InjectRepository(CarForSaleImage)
        public repository: Repository<CarForSaleImage>
    ) {}
}
