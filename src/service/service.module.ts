import { Module } from '@nestjs/common'
import { ServiceService } from './app/service.service'
import { ServiceRepository } from './infra/service.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Service } from './domaine/service.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Service])],
    providers: [ServiceService, ServiceRepository],
    exports: [ServiceService, ServiceRepository],
})
export class ServiceModule {}
