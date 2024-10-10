import { Module } from '@nestjs/common'
import { CarForSaleService } from './app/car-for-sale.service'
import { CarForSaleRepository } from './infra/car-for-sale.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSale } from './domaine/car-for-sale.entity'
import { UserModule } from '../user/user.module'
import { RoleModule } from '../role/role.module'
//import { CarForSaleController } from './app/car-for-sale.controller'

@Module({
    imports: [TypeOrmModule.forFeature([CarForSale]), RoleModule, UserModule],
    providers: [CarForSaleService, CarForSaleRepository],
    exports: [CarForSaleService, CarForSaleRepository],
    //controllers: [CarForSaleController],
})
export class CarForSaleModule {}
