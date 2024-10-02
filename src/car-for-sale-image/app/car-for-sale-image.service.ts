import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { CarForSaleImageRepository } from '../infra/car-for-sale-image.repository'

@Injectable()
export class CarForSaleImageService extends BaseService {
    constructor(private readonly carForSaleImageRepository: CarForSaleImageRepository) {
        super('CarForSaleImageService')
    }

    getCarForSaleImages = async () => {
        return await this.carForSaleImageRepository.repository.find()
    }

    createCarForSaleImage = () => {
        return this.carForSaleImageRepository.repository.create()
    }
}
