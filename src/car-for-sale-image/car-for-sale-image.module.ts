import { Module } from '@nestjs/common'
import { CarForSaleImageService } from './app/car-for-sale-image.service'
import { CarForSaleImageRepository } from './infra/car-for-sale-image.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSaleImage } from './domaine/car-for-sale-image.entity'

@Module({
    imports: [TypeOrmModule.forFeature([CarForSaleImage])],
    providers: [CarForSaleImageService, CarForSaleImageRepository],
    exports: [CarForSaleImageService, CarForSaleImageRepository],
})
export class CarForSaleImageModule {}
