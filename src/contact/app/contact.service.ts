import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { ContactRepository } from '../infra/contact.repository'

@Injectable()
export class ContactService extends BaseService {
    constructor(private readonly contactRepository: ContactRepository) {
        super('ContactService')
    }

    getContacts = async () => {
        return await this.contactRepository.repository.find()
    }

    createContact = () => {
        return this.contactRepository.repository.create()
    }
}
