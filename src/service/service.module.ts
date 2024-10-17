import { Module } from '@nestjs/common'
import { ServiceService } from './app/service.service'
import { ServiceRepository } from './infra/service.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Service } from './domaine/service.entity'
import { ServiceController } from './app/service.controller'
import { UserModule } from '../user/user.module'

@Module({
    imports: [TypeOrmModule.forFeature([Service]), UserModule],
    providers: [ServiceService, ServiceRepository],
    exports: [ServiceService, ServiceRepository],
    controllers: [ServiceController],
})
export class ServiceModule {}
