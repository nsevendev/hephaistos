import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Service } from './domaine/service.entity'
import { ServiceService } from './app/service.service'
import { ServiceRepository } from './infra/service.repository'
import { CreateServiceDto } from './app/create-service.dto'
import { UserService } from '../user/app/user.service'
import { UserModule } from '../user/user.module'
import { ServiceController } from './app/service.controller'
import { RoleService } from '../role/app/role.service'
import { RoleModule } from '../role/role.module'
import { CreateUserDto } from '../user/app/create-user.dto'
import { NotFoundException } from '@nestjs/common'

describe('ServiceService', () => {
    let serviceService: ServiceService
    let serviceController: ServiceController
    let serviceRepository: ServiceRepository
    let userService: UserService
    let roleService: RoleService
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Service]), UserModule, RoleModule],
            controllers: [ServiceController],
            providers: [ServiceService, ServiceRepository],
        }).compile()

        serviceService = module.get<ServiceService>(ServiceService)
        serviceController = module.get<ServiceController>(ServiceController)
        serviceRepository = module.get<ServiceRepository>(ServiceRepository)
        userService = module.get<UserService>(UserService)
        roleService = module.get<RoleService>(RoleService)
    })

    describe('Service', () => {
        it('ServiceService est défini', () => {
            expect(serviceService).toBeDefined()
        })

        it('ServiceRepository est défini', () => {
            expect(serviceRepository).toBeDefined()
        })

        it('ServiceService.getServices avec aucun service', async () => {
            const services = await serviceService.getServices([])

            expect(services).toEqual([])
        })

        it('ServiceService.createService crée un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Test Service',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceService.createService(serviceData)

            const services = await serviceService.getServices([])

            expect(serviceCreated.name).toEqual(serviceData.name)
            expect(services).toContainEqual(serviceCreated)
        })

        it('ServiceService.getService récupère un service par ID', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Test Service',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceService.createService(serviceData)
            const serviceRetrieved = await serviceService.getServices([serviceCreated.id])

            expect(serviceRetrieved).toEqual([serviceCreated])
        })

        it('ServiceService.deleteService supprime un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Test Service',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceService.createService(serviceData)
            await serviceService.deleteService([serviceCreated.id])

            const services = await serviceService.getServices([])

            expect(services).toEqual([])
        })

        it("ServiceService.deleteService retourne une erreur si le service n'existe pas", async () => {
            const invalidServiceId = 1111

            await expect(serviceService.deleteService([invalidServiceId])).rejects.toThrow(
                `Les services avec les IDs suivants sont introuvables : ${invalidServiceId}.`
            )
        })
    })

    describe('ServiceController', () => {
        it('ServiceController est défini', () => {
            expect(serviceController).toBeDefined()
        })

        it('createService crée un nouveau service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Test Service Controller',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceController.createService(serviceData)

            expect(serviceCreated.name).toEqual(serviceData.name)
        })

        it('getServices retourne 1 service', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Reprogrammation',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceService.createService(serviceData)
            const serviceRetrieved = await serviceController.getServices([serviceCreated.id])

            expect(serviceRetrieved).toEqual([serviceCreated])
        })

        it('getServices retourne plusieurs service', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData1: CreateServiceDto = {
                name: 'Reprogrammation',
                created_by: userCreated.id,
            }

            const serviceData2: CreateServiceDto = {
                name: 'Reprogrammation',
                created_by: userCreated.id,
            }

            const serviceCreated1 = await serviceService.createService(serviceData1)
            const serviceCreated2 = await serviceService.createService(serviceData2)
            const serviceRetrieved = await serviceController.getServices([
                serviceCreated1.id,
                serviceCreated2.id,
            ])

            expect(serviceRetrieved).toEqual([serviceCreated1, serviceCreated2])
        })

        it("getServices retourne une erreur si l'un des id donné est introuvable", async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Reprogrammation',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceService.createService(serviceData)
            await expect(serviceController.getServices([serviceCreated.id, 1111])).rejects.toThrow(
                NotFoundException
            )
        })

        it("getServices retourne une erreur si aucun service n'est trouvé", async () => {
            await expect(serviceController.getServices([9999])).rejects.toThrow(NotFoundException)
        })

        it('deleteService supprime un service par ID', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const serviceData: CreateServiceDto = {
                name: 'Test Service to Delete',
                created_by: userCreated.id,
            }

            const serviceCreated = await serviceService.createService(serviceData)
            await serviceController.deleteService(serviceCreated.id)

            const services = await serviceService.getServices([])

            expect(services).not.toContainEqual(serviceCreated)
        })

        it("deleteService retourne une erreur si le service n'existe pas", async () => {
            const invalidServiceId = 9999

            await expect(serviceController.deleteService(invalidServiceId)).rejects.toThrow(NotFoundException)
        })
    })
})
