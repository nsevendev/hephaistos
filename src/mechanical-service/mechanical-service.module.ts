import { Module } from '@nestjs/common'
import { MechanicalServiceService } from './app/mechanical-service.service'
import { MechanicalServiceRepository } from './infra/mechanical-service.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { MechanicalService } from './domaine/mechanical-service.entity'

@Module({
    imports: [TypeOrmModule.forFeature([MechanicalService])],
    providers: [MechanicalServiceService, MechanicalServiceRepository],
    exports: [MechanicalServiceService, MechanicalServiceRepository],
})
export class MechanicalServiceModule {}
