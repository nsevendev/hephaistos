import { Test, TestingModule } from '@nestjs/testing'
import { RoleController } from './app/role.controller'
import { RoleService } from './app/role.service'
import { DataSource } from 'typeorm'
import { RoleRepository } from './infra/role.repository'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Role } from './domaine/role.entity'
import { CreateRoleDto } from './app/create-role.dto'
import { NotFoundException } from '@nestjs/common'

describe('RoleModule', () => {
    let roleController: RoleController
    let module: TestingModule
    let roleService: RoleService
    let createRoleDto: CreateRoleDto[]
    let dataSource: DataSource

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Role])],
            controllers: [RoleController],
            providers: [RoleService, RoleRepository],
        }).compile()

        createRoleDto = [{ name: 'admin' }, { name: 'employee' }]
        roleController = module.get<RoleController>(RoleController)
        roleService = module.get<RoleService>(RoleService)
    })

    afterEach(async () => {
        dataSource = module.get<DataSource>(DataSource)
        const entities = dataSource.entityMetadatas

        for (const entity of entities) {
            const repository = dataSource.getRepository(entity.name)
            await repository.query(`TRUNCATE TABLE "${entity.tableName}" RESTART IDENTITY CASCADE;`)
        }

        await dataSource.destroy()
    })

    describe('Controller', () => {
        it('RoleController est defini', () => {
            expect(roleController).toBeDefined()
        })

        it('RoleController.createRole', async () => {
            const result = await roleController.createRole(createRoleDto[0])

            expect(result.name).toEqual('admin')
        })

        it('RoleController.getAllRoles', async () => {
            await roleController.createRole(createRoleDto[0])
            await roleController.createRole(createRoleDto[1])

            const result = await roleController.getAllRoles()
            expect(result).toHaveLength(2)
            expect(result[0].name).toEqual('admin')
            expect(result[1].name).toEqual('employee')
        })

        it('RoleController.getRoleById - rôle existant', async () => {
            const createdRole = await roleController.createRole(createRoleDto[0])
            const result = await roleController.getRoleById(createdRole.id)

            expect(result).toBeDefined()
            expect(result.id).toEqual(createdRole.id)
            expect(result.name).toEqual('admin')
        })

        it('RoleController.getRoleById - rôle inexistant', async () => {
            await expect(roleController.getRoleById(999)).rejects.toThrow(NotFoundException)
        })

        it("RoleController.deleteRole - suppression d'un rôle existant", async () => {
            const createdRole = await roleController.createRole(createRoleDto[0])
            await roleController.deleteRole(createdRole.id)

            await expect(roleService.getOneRole(createdRole.id)).rejects.toThrow(NotFoundException)
        })

        it('RoleController.deleteRole - rôle inexistant', async () => {
            await expect(roleController.deleteRole(999)).rejects.toThrow(NotFoundException)
        })

        it('RoleController.deleteManyRoles - suppression de plusieurs rôles', async () => {
            const createdRole1 = await roleController.createRole(createRoleDto[0])
            const createdRole2 = await roleController.createRole(createRoleDto[1])

            await roleController.deleteManyRoles([createdRole1.id, createdRole2.id])
            const result = await roleService.getManyRoles([createdRole1.id, createdRole2.id])

            expect(result).toHaveLength(0)
        })

        it('RoleController.deleteManyRoles - rôles inexistants', async () => {
            await expect(roleController.deleteManyRoles([1111, 2222])).rejects.toThrow(NotFoundException)
        })
    })

    describe('Service', () => {
        it('RoleService est defini', () => {
            expect(roleService).toBeDefined()
        })

        it('RoleService.createRole', async () => {
            const result = await roleService.createRole(createRoleDto[0])

            expect(result.name).toEqual('admin')
        })

        it('RoleService.getOneRole - rôle existant', async () => {
            const createdRole = await roleService.createRole(createRoleDto[0])
            const result = await roleService.getOneRole(createdRole.id)

            expect(result).toBeDefined()
            expect(result.id).toEqual(createdRole.id)
            expect(result.name).toEqual('admin')
        })

        it('RoleService.getOneRole - rôle inexistant', async () => {
            await expect(roleService.getOneRole(999)).rejects.toThrow(NotFoundException)
        })

        it('RoleService.getManyRoles - plusieurs rôles existants', async () => {
            const createdRole1 = await roleService.createRole(createRoleDto[0])
            const createdRole2 = await roleService.createRole(createRoleDto[1])
            const result = await roleService.getManyRoles([createdRole1.id, createdRole2.id])

            expect(result).toHaveLength(2)
            expect(result[0].name).toEqual('admin')
            expect(result[1].name).toEqual('employee')
        })

        it('RoleService.getManyRoles - sans ID', async () => {
            await roleService.createRole(createRoleDto[0])
            await roleService.createRole(createRoleDto[1])
            const result = await roleService.getManyRoles()

            expect(result).toHaveLength(2)
        })

        it("RoleService.deleteOneRole - suppression d'un rôle existant", async () => {
            const createdRole = await roleService.createRole(createRoleDto[0])
            await roleService.deleteOneRole(createdRole.id)

            await expect(roleService.getOneRole(createdRole.id)).rejects.toThrow(NotFoundException)
        })

        it('RoleService.deleteOneRole - rôle inexistant', async () => {
            await expect(roleService.deleteOneRole(999)).rejects.toThrow(NotFoundException)
        })

        it('RoleService.deleteManyRoles - suppression de plusieurs rôles', async () => {
            const createdRole1 = await roleService.createRole(createRoleDto[0])
            const createdRole2 = await roleService.createRole(createRoleDto[1])
            await roleService.deleteManyRoles([createdRole1.id, createdRole2.id])

            await expect(roleService.getManyRoles([createdRole1.id, createdRole2.id])).resolves.toHaveLength(
                0
            )
        })

        it('RoleService.deleteManyRoles - rôles inexistants', async () => {
            await expect(roleService.deleteManyRoles([1111, 2222])).rejects.toThrow(NotFoundException)
        })
    })
})
