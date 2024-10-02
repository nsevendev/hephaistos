import { Module } from '@nestjs/common'
import { CarForSaleService } from './app/car-for-sale.service'
import { CarForSaleRepository } from './infra/car-for-sale.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSale } from './domaine/car-for-sale.entity'

@Module({
    imports: [TypeOrmModule.forFeature([CarForSale])],
    providers: [CarForSaleService, CarForSaleRepository],
    exports: [CarForSaleService, CarForSaleRepository],
})
export class CarForSaleModule {}
