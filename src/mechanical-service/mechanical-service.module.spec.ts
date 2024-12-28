import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { UserService } from '../user/app/user.service'
import { RoleModule } from '../role/role.module'
import { RoleService } from '../role/app/role.service'
import { MechanicalServiceService } from './app/mechanical-service.service'
import { MechanicalServiceController } from './app/mechanical-service.controller'
import { MechanicalServiceRepository } from './infra/mechanical-service.repository'
import { MechanicalService } from './domaine/mechanical-service.entity'
import { UserModule } from '../user/user.module'
import { CreateMechanicalServiceDto } from './app/create-mechanical-service.dto'
import { CreateUserDto } from '../user/app/create-user.dto'
import { NotFoundException } from '@nestjs/common'
import { UpdateMechanicalServiceDto } from './app/update-mechanical-service.dto'

describe('MechanicalServiceModule', () => {
    let mechanicalServiceService: MechanicalServiceService
    let mechanicalServiceController: MechanicalServiceController
    let mechanicalServiceRepository: MechanicalServiceRepository
    let userService: UserService
    let roleService: RoleService
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule,
                TypeOrmModule.forFeature([MechanicalService]),
                RoleModule,
                UserModule,
            ],
            controllers: [MechanicalServiceController],
            providers: [MechanicalServiceService, MechanicalServiceRepository],
        }).compile()

        mechanicalServiceService = module.get<MechanicalServiceService>(MechanicalServiceService)
        mechanicalServiceController = module.get<MechanicalServiceController>(MechanicalServiceController)
        mechanicalServiceRepository = module.get<MechanicalServiceRepository>(MechanicalServiceRepository)
        roleService = module.get<RoleService>(RoleService)
        userService = module.get<UserService>(UserService)
    })

    describe('Service', () => {
        it('MechanicalServiceService est défini', () => {
            expect(mechanicalServiceService).toBeDefined()
        })

        it('MechanicalServiceRepository est défini', () => {
            expect(mechanicalServiceRepository).toBeDefined()
        })

        it('UserService est défini', () => {
            expect(userService).toBeDefined()
        })

        it('MechanicalServiceService.createMechanicalService crée un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Vidange',
                lower_price: 100,
                created_by: userCreated.id,
            }

            const result = await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)

            expect(result).toEqual(
                expect.objectContaining({
                    name: 'Vidange',
                    lower_price: 100,
                    created_by: expect.objectContaining({
                        id: userCreated.id,
                        email: userCreated.email,
                    }),
                })
            )
        })

        it('MechanicalServiceService.getMechanicalService récupère tous les services quand un tableau vide est donnée', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user2',
                email: 'user2@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Révision',
                lower_price: 150,
                created_by: userCreated.id,
            }

            const createdService =
                await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)
            const result = await mechanicalServiceService.getMechanicalServices([])

            expect(result[0]).toEqual(
                expect.objectContaining({
                    id: createdService.id,
                    name: 'Révision',
                    lower_price: 150,
                    created_by: expect.objectContaining({
                        id: userCreated.id,
                        email: userCreated.email,
                    }),
                })
            )
        })

        it('MechanicalServiceService.getMechanicalService récupère un service par ID', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user2',
                email: 'user2@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Révision',
                lower_price: 150,
                created_by: userCreated.id,
            }

            const createdService =
                await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)
            const result = await mechanicalServiceService.getMechanicalServices([createdService.id])

            expect(result[0]).toEqual(
                expect.objectContaining({
                    id: createdService.id,
                    name: 'Révision',
                    lower_price: 150,
                    created_by: expect.objectContaining({
                        id: userCreated.id,
                        email: userCreated.email,
                    }),
                })
            )
        })

        it('MechanicalServiceService.getMechanicalServiceByFilter récupère des services par filtre de nom', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user5',
                email: 'user5@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const service1: CreateMechanicalServiceDto = {
                name: 'Vidange',
                lower_price: 30,
                created_by: userCreated.id,
            }
            const service2: CreateMechanicalServiceDto = {
                name: 'Révision des freins',
                lower_price: 300,
                created_by: userCreated.id,
            }

            await mechanicalServiceService.createMechanicalService(service1)
            await mechanicalServiceService.createMechanicalService(service2)

            const result = await mechanicalServiceService.getMechanicalServicesByFilter('Révision')

            expect(result.length).toBeGreaterThan(0)
            expect(result).toEqual(
                expect.arrayContaining([
                    expect.objectContaining({
                        name: 'Révision des freins',
                        created_by: expect.objectContaining({
                            id: userCreated.id,
                            email: userCreated.email,
                        }),
                    }),
                ])
            )
        })

        it('MechanicalServiceService.updateMechanicalService met à jour un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user3',
                email: 'user3@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Changement de pneus',
                lower_price: 200,
                created_by: userCreated.id,
            }

            const createdService =
                await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)

            const updateData: UpdateMechanicalServiceDto = {
                name: 'Changement de pneus et équilibrage',
                lower_price: 250,
            }

            const updatedService = await mechanicalServiceService.updateMechanicalService(
                createdService.id,
                updateData
            )

            expect(updatedService).toEqual(
                expect.objectContaining({
                    name: 'Changement de pneus et équilibrage',
                    lower_price: 250,
                    created_by: expect.objectContaining({
                        id: userCreated.id,
                        email: userCreated.email,
                    }),
                })
            )
        })

        it('MechanicalServiceService.deleteMechanicalService supprime un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user4',
                email: 'user4@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Entretien général',
                lower_price: 300,
                created_by: userCreated.id,
            }

            const createdService =
                await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)

            await mechanicalServiceService.deleteMechanicalServices([createdService.id])

            await expect(mechanicalServiceService.getMechanicalServices([])).resolves.toEqual([])
        })

        it("MechanicalServiceService.deleteMechanicalService retourne une erreur si le service n'existe pas", async () => {
            const invalidServiceId = 999

            await expect(
                mechanicalServiceService.deleteMechanicalServices([invalidServiceId])
            ).rejects.toThrow(NotFoundException)
        })
    })

    describe('Controller', () => {
        it('MechanicalServiceService est défini', () => {
            expect(mechanicalServiceService).toBeDefined()
        })

        it('MechanicalServiceController est défini', () => {
            expect(mechanicalServiceController).toBeDefined()
        })

        it('MechanicalServiceRepository est défini', () => {
            expect(mechanicalServiceRepository).toBeDefined()
        })

        it('UserService est défini', () => {
            expect(userService).toBeDefined()
        })

        it('MechanicalServiceController.getMechanicalService récupère tous les services', async () => {
            const result = await mechanicalServiceController.getMechanicalServices([])
            expect(result).toBeDefined()
        })

        it("MechanicalServiceController.getMechanicalService renvoie une erreur si aucun service n'est trouvé avec les IDs fournis", async () => {
            await expect(mechanicalServiceController.getMechanicalServices([999])).rejects.toThrow(
                NotFoundException
            )
        })

        it('MechanicalServiceController.getMechanicalServiceByFilter récupère des services filtrés par nom', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user6',
                email: 'user6@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const service1: CreateMechanicalServiceDto = {
                name: 'Vidange',
                lower_price: 30,
                created_by: userCreated.id,
            }
            const service2: CreateMechanicalServiceDto = {
                name: 'Révision des freins',
                lower_price: 300,
                created_by: userCreated.id,
            }

            await mechanicalServiceService.createMechanicalService(service1)
            await mechanicalServiceService.createMechanicalService(service2)

            const result = await mechanicalServiceController.getMechanicalServicesByFilter('Révision')

            expect(result.length).toBeGreaterThan(0)
            expect(result).toEqual(
                expect.arrayContaining([
                    expect.objectContaining({
                        name: 'Révision des freins',
                        created_by: expect.objectContaining({
                            id: userCreated.id,
                            email: userCreated.email,
                        }),
                    }),
                ])
            )
        })

        it('MechanicalServiceController.createMechanicalService crée un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user7',
                email: 'user7@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Contrôle technique',
                lower_price: 120,
                created_by: userCreated.id,
            }

            const result =
                await mechanicalServiceController.createMechanicalService(createMechanicalServiceDto)

            expect(result).toEqual(
                expect.objectContaining({
                    name: 'Contrôle technique',
                    lower_price: 120,
                    created_by: expect.objectContaining({
                        id: userCreated.id,
                        email: userCreated.email,
                    }),
                })
            )
        })

        it('MechanicalServiceController.updateMechanicalService met à jour un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user8',
                email: 'user8@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Changement de filtre',
                lower_price: 75,
                created_by: userCreated.id,
            }

            const createdService =
                await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)

            const updateData: UpdateMechanicalServiceDto = {
                name: 'Changement de filtre et contrôle',
                lower_price: 85,
            }

            const updatedService = await mechanicalServiceController.updateMechanicalService(
                createdService.id,
                updateData
            )

            expect(updatedService).toEqual(
                expect.objectContaining({
                    name: 'Changement de filtre et contrôle',
                    lower_price: 85,
                    created_by: expect.objectContaining({
                        id: userCreated.id,
                        email: userCreated.email,
                    }),
                })
            )
        })

        it('MechanicalServiceController.deleteMechanicalService supprime un service avec succès', async () => {
            const role = await roleService.createRole({ name: 'admin' })

            const userData: CreateUserDto = {
                username: 'user9',
                email: 'user9@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const createMechanicalServiceDto: CreateMechanicalServiceDto = {
                name: 'Entretien des freins',
                lower_price: 250,
                created_by: userCreated.id,
            }

            const createdService =
                await mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)

            await mechanicalServiceController.deleteMechanicalServices([createdService.id])

            await expect(mechanicalServiceService.getMechanicalServices([])).resolves.toEqual([])
        })

        it("MechanicalServiceController.deleteMechanicalService retourne une erreur si le service n'existe pas", async () => {
            const invalidServiceId = 999

            await expect(
                mechanicalServiceController.deleteMechanicalServices([invalidServiceId])
            ).rejects.toThrow(NotFoundException)
        })
    })
})
