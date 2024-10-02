import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { CarForSale } from '../domaine/car-for-sale.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class CarForSaleRepository {
    constructor(
        @InjectRepository(CarForSale)
        public repository: Repository<CarForSale>
    ) {}
}
