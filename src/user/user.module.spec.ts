import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { User } from './domaine/user.entity'
import { UserService } from './app/user.service'
import { UserRepository } from './infra/user.repository'
import { UserController } from './app/user.controller'
import { CreateUserDto } from './app/create-user.dto'
import { UpdateUserDto } from './app/update-user.dto'
import { RoleModule } from '../role/role.module'
import { RoleService } from '../role/app/role.service'
import { NotFoundException } from '@nestjs/common'

describe('UserModule', () => {
    let userService: UserService
    let userController: UserController
    let userRepository: UserRepository
    let roleService: RoleService
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([User]), RoleModule],
            controllers: [UserController],
            providers: [UserService, UserRepository],
        }).compile()

        userService = module.get<UserService>(UserService)
        userController = module.get<UserController>(UserController)
        userRepository = module.get<UserRepository>(UserRepository)
        roleService = module.get<RoleService>(RoleService)
    })

    describe('Service', () => {
        it('UserService est défini', () => {
            expect(userService).toBeDefined()
        })

        it('UserRepository est défini', () => {
            expect(userRepository).toBeDefined()
        })

        it('UserService.getUsers avec aucun user', async () => {
            const users = await userService.getUsers()

            expect(users).toEqual([])
        })

        it('UserService.createUser crée un user avec succès', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)

            const users = await userService.getUsers()

            expect(userCreated.role.id).toEqual(role.id)
            expect(users).toContainEqual(userCreated)
        })

        it('UserService.getUser récupère un user par ID', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)
            const userRetrieved = await userService.getUser(userCreated.id)

            expect(userRetrieved).toEqual(userCreated)
        })

        it("UserService.updateUser met à jour les informations de l'user", async () => {
            const role = await roleService.createRole({ name: 'user' })
            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)
            const updatedData: UpdateUserDto = { username: 'user1_updated' }
            const updatedUser = await userService.updateUser(userCreated.id, updatedData)

            expect(updatedUser.username).toBe('user1_updated')
        })

        it('UserService.deleteUser supprime un user avec succès', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const userCreated = await userService.createUser(userData)
            await userService.deleteUser(userCreated.id)

            const users = await userService.getUsers()

            expect(users).toEqual([])
        })

        it("UserService.deleteUser retourne une erreur si l'user n'existe pas", async () => {
            const invalidUserId = 1111

            await expect(userService.deleteUser(invalidUserId)).rejects.toThrow(`Une erreur est survenu`)
        })
    })

    describe('Controller', () => {
        it('UserController est défini', () => {
            expect(userController).toBeDefined()
        })

        it('UserController.createUser crée un user', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userData: CreateUserDto = {
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            }

            const result = await userController.createUser(userData)

            expect(result.username).toEqual(userData.username)
            expect(result.role.id).toEqual(role.id)
        })

        it('UserController.getAllUsers récupère tous les utilisateurs', async () => {
            const role = await roleService.createRole({ name: 'user' })

            await userService.createUser({
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            })

            const result = await userController.getAllUsers()

            expect(result).toHaveLength(1)
        })

        it('UserController.getUserById - utilisateur existant', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userCreated = await userService.createUser({
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            })

            const result = await userController.getUserById(userCreated.id)

            expect(result).toBeDefined()
            expect(result.id).toEqual(userCreated.id)
        })

        it('UserController.getUserById - utilisateur inexistant', async () => {
            await expect(userController.getUserById(999)).rejects.toThrow(NotFoundException)
        })

        it('UserController.updateUser met à jour un utilisateur', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userCreated = await userService.createUser({
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            })

            const updatedData: UpdateUserDto = { username: 'user_updated' }
            const result = await userController.updateUser(userCreated.id, updatedData)

            expect(result.username).toEqual('user_updated')
        })

        it('UserController.deleteUser supprime un utilisateur', async () => {
            const role = await roleService.createRole({ name: 'user' })

            const userCreated = await userService.createUser({
                username: 'user1',
                email: 'user1@example.com',
                password: 'password123',
                role: role.id,
            })

            await userController.deleteUser(userCreated.id)

            await expect(userService.getUser(userCreated.id)).rejects.toThrow(NotFoundException)
        })

        it('UserController.deleteUser - utilisateur inexistant', async () => {
            await expect(userController.deleteUser(999)).rejects.toThrow(NotFoundException)
        })
    })

    // TODO : ajouter test login logout et création token
})
