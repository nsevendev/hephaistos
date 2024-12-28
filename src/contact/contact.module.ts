import { Module } from '@nestjs/common'
import { ContactService } from './app/contact.service'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Contact } from './domaine/contact.entity'
import { ContactController } from './app/contact.controller'
import { ContactRepository } from './infra/contact.repository'
import { ServiceModule } from '../service/service.module'
import { MechanicalServiceModule } from '../mechanical-service/mechanical-service.module'
import { RoleModule } from '../role/role.module'
import { UserModule } from '../user/user.module'

@Module({
    imports: [
        TypeOrmModule.forFeature([Contact]),
        ServiceModule,
        MechanicalServiceModule,
        RoleModule,
        UserModule,
    ],
    providers: [ContactService, ContactRepository],
    exports: [ContactService, ContactRepository],
    controllers: [ContactController],
})
export class ContactModule {}
