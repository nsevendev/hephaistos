import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { ContactService } from './app/contact.service'
import { ContactController } from './app/contact.controller'
import { ContactRepository } from './infra/contact.repository'
import { CreateContactDto } from './app/create-contact.dto'
import { UpdateContactDto } from './app/update-contact.dto'
import { Contact } from './domaine/contact.entity'
import { Service } from '../service/domaine/service.entity'
import { MechanicalService } from '../mechanical-service/domaine/mechanical-service.entity'
import { BadRequestException, NotFoundException } from '@nestjs/common'
import { ServiceService } from '../service/app/service.service'
import { MechanicalServiceService } from '../mechanical-service/app/mechanical-service.service'
import { ServiceModule } from '../service/service.module'
import { MechanicalServiceModule } from '../mechanical-service/mechanical-service.module'
import { RoleService } from '../role/app/role.service'
import { CreateUserDto } from '../user/app/create-user.dto'
import { UserService } from '../user/app/user.service'
import { RoleModule } from '../role/role.module'
import { UserModule } from '../user/user.module'

describe('ContactModule', () => {
    let contactService: ContactService
    let contactController: ContactController
    let contactRepository: ContactRepository
    let serviceService: ServiceService
    let mechanicalServiceService: MechanicalServiceService
    let roleService: RoleService
    let userService: UserService
    let module: TestingModule
    let userCreated: any

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule,
                TypeOrmModule.forFeature([Contact, Service, MechanicalService]),
                ServiceModule,
                MechanicalServiceModule,
                RoleModule,
                UserModule,
            ],
            controllers: [ContactController],
            providers: [ContactService, ContactRepository],
        }).compile()

        contactService = module.get<ContactService>(ContactService)
        contactController = module.get<ContactController>(ContactController)
        contactRepository = module.get<ContactRepository>(ContactRepository)
        serviceService = module.get<ServiceService>(ServiceService)
        mechanicalServiceService = module.get<MechanicalServiceService>(MechanicalServiceService)
        roleService = module.get<RoleService>(RoleService)
        userService = module.get<UserService>(UserService)
    })

    beforeEach(async () => {
        const role = await roleService.createRole({ name: 'admin' })

        const userData: CreateUserDto = {
            username: 'user1',
            email: 'user1@example.com',
            password: 'password123',
            role: role.id,
        }

        userCreated = await userService.createUser(userData)
    })

    describe('Service', () => {
        it('ContactService est défini', () => {
            expect(contactService).toBeDefined()
        })

        it('ContactRepository est défini', () => {
            expect(contactRepository).toBeDefined()
        })

        it('ServiceRepository est défini', () => {
            expect(serviceService).toBeDefined()
        })

        it('MechanicalServiceService est défini', () => {
            expect(mechanicalServiceService).toBeDefined()
        })

        it('ContactService.createContact devrait créer un contact avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const mechanicalService = await mechanicalServiceService.createMechanicalService({
                name: 'Mechanical Service Test',
                lower_price: 100,
                created_by: userCreated.id,
            })

            const createContactDto: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: mechanicalService.id,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            const contact = await contactService.createContact(createContactDto)

            expect(contact).toBeDefined()
            expect(contact.firstname).toEqual('John')
            expect(contact.lastname).toEqual('Doe')
            expect(contact.service.id).toEqual(service.id)
            expect(contact.mechanical_service.id).toEqual(mechanicalService.id)
        })

        it("ContactService.createContact devrait lancer une erreur si le service n'existe pas", async () => {
            const createContactDto: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: 999,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            await expect(contactService.createContact(createContactDto)).rejects.toThrow(BadRequestException)
        })

        it("ContactService.createContact devrait lancer une erreur si le service mécanique n'existe pas", async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })

            const createContactDto: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: 999,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            await expect(contactService.createContact(createContactDto)).rejects.toThrow(BadRequestException)
        })

        it('ContactService.getContact devrait récupérer tous les contacts', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const contactData: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            await contactService.createContact(contactData)

            const contacts = await contactService.getContact()

            expect(contacts).toHaveLength(1)
            expect(contacts[0].firstname).toEqual('John')
        })

        it('ContactService.getContact devrait récupérer un contact par ID', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const contactData: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            const contact = await contactService.createContact(contactData)
            const retrievedContact = await contactService.getContact([contact.id])

            expect(retrievedContact).toHaveLength(1)
            expect(retrievedContact[0].firstname).toEqual('John')
        })

        it('ContactService.updateContact devrait mettre à jour un contact avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const mechanicalService = await mechanicalServiceService.createMechanicalService({
                name: 'Mechanical Service Test',
                lower_price: 100,
                created_by: userCreated.id,
            })
            const contactData: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: mechanicalService.id,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            const contact = await contactService.createContact(contactData)
            const updateData: UpdateContactDto = { firstname: 'Jane' }

            const updatedContact = await contactService.updateContact(contact.id, updateData)

            expect(updatedContact.firstname).toEqual('Jane')
        })

        it("ContactService.updateContact devrait lancer une erreur si le contact n'existe pas", async () => {
            const updateData: UpdateContactDto = { firstname: 'Jane' }

            await expect(contactService.updateContact(999, updateData)).rejects.toThrow(NotFoundException)
        })

        it('ContactService.deleteContact devrait supprimer un contact avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const contactData: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            const contact = await contactService.createContact(contactData)
            await contactService.deleteContact([contact.id])

            const contacts = await contactService.getContact()

            expect(contacts).toHaveLength(0)
        })

        it("ContactService.deleteContact devrait lancer une erreur si aucun ID n'est fourni", async () => {
            await expect(contactService.deleteContact([])).rejects.toThrow(BadRequestException)
        })

        it("ContactService.deleteContact devrait lancer une erreur si le contact n'existe pas", async () => {
            await expect(contactService.deleteContact([999])).rejects.toThrow(NotFoundException)
        })
    })

    describe('Controller', () => {
        it('ContactController est défini', () => {
            expect(contactController).toBeDefined()
        })

        it('ContactController.createContact crée un contact', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const mechanicalService = await mechanicalServiceService.createMechanicalService({
                name: 'Mechanical Service Test',
                lower_price: 100,
                created_by: userCreated.id,
            })

            const createContactDto: CreateContactDto = {
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: mechanicalService.id,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            }

            const result = await contactController.createContact(createContactDto)

            expect(result.firstname).toEqual(createContactDto.firstname)
            expect(result.service.id).toEqual(service.id)
        })

        it('ContactController.getContact récupère tous les contacts', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            })

            const result = await contactController.getContact([])

            expect(result).toHaveLength(1)
            expect(result[0].firstname).toEqual('John')
        })

        it('ContactController.getContactById récupère un contact par ID', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const contactCreated = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            })

            const result = await contactController.getContact([contactCreated.id])

            expect(result).toBeDefined()
            expect(result[0].id).toEqual(contactCreated.id)
        })

        it('ContactController.updateContact met à jour un contact', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })

            const contactCreated = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            })

            const updateData: UpdateContactDto = { firstname: 'Jane' }
            const result = await contactController.updateContact(contactCreated.id, updateData)

            expect(result.firstname).toEqual('Jane')
        })

        it('ContactController.deleteContact supprime un contact', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })

            const contactCreated = await contactService.createContact({
                firstname: 'John',
                lastname: 'Doe',
                email: 'john.doe@example.com',
                phone: '1234567890',
                service_id: service.id,
                mechanical_service_id: null,
                car_motorisation: 'Diesel',
                car_year: 2020,
                car_model: 'Model X',
                car_manufacturer: 'Manufacturer Y',
                message: 'Test message',
            })

            await contactController.deleteContact([contactCreated.id])

            const contactFromDb = await contactRepository.repository.findOne({
                where: { id: contactCreated.id },
            })

            expect(contactFromDb).toBeNull()
        })
    })
})
