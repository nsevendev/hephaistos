import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Contact } from '../domaine/contact.entity'

@Injectable()
export class ContactRepository {
    constructor(
        @InjectRepository(Contact)
        public readonly repository: Repository<Contact>
    ) {}
}
