import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Contact } from '../domaine/contact.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class ContactRepository {
    constructor(
        @InjectRepository(Contact)
        public repository: Repository<Contact>
    ) {}
}
