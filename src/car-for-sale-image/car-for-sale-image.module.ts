import { Module } from '@nestjs/common'
import { CarForSaleImageService } from './app/car-for-sale-image.service'
import { CarForSaleImageRepository } from './infra/car-for-sale-image.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSaleImage } from './domaine/car-for-sale-image.entity'
import { UserModule } from '../user/user.module'
import { RoleModule } from '../role/role.module'
import { CarForSaleModule } from '../car-for-sale/car-for-sale.module'
import { CarForSaleImageController } from './app/car-for-sale-image.controller'

@Module({
    imports: [TypeOrmModule.forFeature([CarForSaleImage]), UserModule, RoleModule, CarForSaleModule],
    providers: [CarForSaleImageService, CarForSaleImageRepository],
    exports: [CarForSaleImageService, CarForSaleImageRepository],
    controllers: [CarForSaleImageController],
})
export class CarForSaleImageModule {}
