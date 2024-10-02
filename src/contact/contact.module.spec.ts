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
                DatabaseTestModule, // Utilisation de la base de données pour les tests
                TypeOrmModule.forFeature([Contact]),
            ],
            providers: [ContactService, ContactRepository],
        }).compile()

        contactService = module.get<ContactService>(ContactService)
        contactRepository = module.get<ContactRepository>(ContactRepository)
    })

    describe('Service', () => {
        it('ContactService est défini', () => {
            expect(contactService).toBeDefined()
        })

        it('ContactService.getManyContacts avec aucun contact', async () => {
            const contacts = await contactService.getManyContacts()
            expect(contacts).toEqual([]) // Aucun contact dans la BDD
        })

        it('ContactService.createContact crée un contact avec succès', async () => {
            const contactCreated = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '123456789',
                service: 1, // ID d'un service existant
                message: 'This is a test message',
            })
            const contacts = await contactService.getManyContacts()
            expect(contacts).toContain(contactCreated) // Le contact a bien été créé
        })

        it('ContactService.createContact crée un contact avec des champs requis vide', async () => {
            await expect(
                contactService.createContact({
                    firstname: '',
                    lastname: '',
                    email: '',
                    phone: '123456789',
                    service: 1,
                    message: 'Missing required fields',
                })
            ).rejects.toThrow() // Doit échouer si les champs requis sont manquants
        })

        it('ContactService.getOneContact récupère un contact spécifique', async () => {
            const contactCreated = await contactService.createContact({
                firstname: 'Jane',
                lastname: 'Doe',
                email: 'jane.doe@example.com',
                phone: '987654321',
                service: 1,
                message: 'Test message for getOneContact',
            })

            const foundContact = await contactService.getOneContact(contactCreated.id)
            expect(foundContact).toEqual(contactCreated) // Vérifie que le contact correct est récupéré
        })

        it("ContactService.getOneContact recherche d'un contact qui n'existe pas", async () => {
            const invalidContactId = 9999 // ID inexistant
            await expect(contactService.getOneContact(invalidContactId)).rejects.toThrowError(
                `Le contact avec l'ID ${invalidContactId} n'a pas été trouvé.`
            )
        })

        it('ContactService.deleteOneContact supprime un contact avec succès', async () => {
            const contactCreated = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '123456789',
                service: 1,
                message: 'This contact will be deleted',
            })

            await contactService.deleteOneContact(contactCreated.id) // Suppression du contact
            const contacts = await contactService.getManyContacts()
            expect(contacts).toEqual([]) // La liste des contacts doit être vide après la suppression
        })

        it("ContactService.deleteOneContact supprime un contact qui n'existe pas", async () => {
            const invalidContactId = 9999 // ID inexistant
            await expect(contactService.deleteOneContact(invalidContactId)).rejects.toThrowError(
                `Le contact avec l'ID ${invalidContactId} n'a pas été trouvé.`
            )
        })

        it('ContactService.deleteManyContacts supprime plusieurs contacts avec succès', async () => {
            const contact1 = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '123456789',
                service: 1,
                message: 'First contact to be deleted',
            })
            const contact2 = await contactService.createContact({
                firstname: 'Jane',
                lastname: 'Doe',
                email: 'jane.doe@example.com',
                phone: '987654321',
                service: 1,
                message: 'Second contact to be deleted',
            })

            await contactService.deleteManyContacts([contact1.id, contact2.id])
            const contacts = await contactService.getManyContacts()
            expect(contacts).toEqual([]) // La liste des contacts doit être vide après la suppression
        })

        it('ContactService.getManyContacts récupérer tous les contacts', async () => {
            const contact1 = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '123456789',
                service: 1,
                message: 'First contact message',
            })
            const contact2 = await contactService.createContact({
                firstname: 'Jane',
                lastname: 'Doe',
                email: 'jane.doe@example.com',
                phone: '987654321',
                service: 1,
                message: 'Second contact message',
            })

            const contacts = await contactService.getManyContacts()
            expect(contacts).toEqual([contact1, contact2]) // Vérifie que les deux contacts sont présents
        })
    })
})
