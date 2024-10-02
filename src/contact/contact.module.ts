import { Module } from '@nestjs/common'
import { ContactService } from './app/contact.service'
import { ContactRepository } from './infra/contact.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Contact } from './domaine/contact.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Contact])],
    providers: [ContactService, ContactRepository],
    exports: [ContactService, ContactRepository],
})
export class ContactModule {}
