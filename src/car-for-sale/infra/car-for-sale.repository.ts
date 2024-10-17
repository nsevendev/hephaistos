import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { CarForSale } from '../domaine/car-for-sale.entity'

@Injectable()
export class CarForSaleRepository {
    constructor(
        @InjectRepository(CarForSale)
        public readonly repository: Repository<CarForSale>
    ) {}
}
