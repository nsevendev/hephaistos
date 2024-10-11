import { Module } from '@nestjs/common'
import { MechanicalServiceService } from './app/mechanical-service.service'
import { MechanicalServiceRepository } from './infra/mechanical-service.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { MechanicalService } from './domaine/mechanical-service.entity'
import { RoleModule } from '../role/role.module'
import { MechanicalServiceController } from './app/mechanical-service.controller'
import { UserModule } from '../user/user.module'

@Module({
    imports: [TypeOrmModule.forFeature([MechanicalService]), RoleModule, UserModule],
    providers: [MechanicalServiceService, MechanicalServiceRepository],
    exports: [MechanicalServiceService, MechanicalServiceRepository],
    controllers: [MechanicalServiceController],
})
export class MechanicalServiceModule {}
