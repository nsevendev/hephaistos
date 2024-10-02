import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Contact } from './domaine/contact.entity'
import { ContactService } from './app/contact.service'
import { ContactRepository } from './infra/contact.repository'

describe('ContactModule', () => {
    let contactService: ContactService
    let contactRepository: ContactRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Contact]),
            ],
            providers: [ContactService, ContactRepository],
        }).compile()

        contactService = module.get<ContactService>(ContactService)
        contactRepository = module.get<ContactRepository>(ContactRepository)
    })

    describe('Service', () => {
        it('ContactService est defini', () => {
            expect(contactService).toBeDefined()
        })

        it('ContactService.getContacts avec aucun contact', async () => {
            const contacts = await contactService.getContacts()
            expect(contacts).toEqual([])
        })

        it('ContactService.getContacts avec contact', async () => {
            const contactCreated = await contactService.createContact()
            const contacts = await contactService.getContacts()
            expect(contacts).toEqual([contactCreated])
        })
    })
})
