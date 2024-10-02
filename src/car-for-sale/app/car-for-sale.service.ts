import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { CarForSaleRepository } from '../infra/car-for-sale.repository'

@Injectable()
export class CarForSaleService extends BaseService {
    constructor(private readonly carForSaleRepository: CarForSaleRepository) {
        super('CarForSaleService')
    }

    getCarForSales = async () => {
        return await this.carForSaleRepository.repository.find()
    }

    createCarForSale = () => {
        return this.carForSaleRepository.repository.create()
    }
}
