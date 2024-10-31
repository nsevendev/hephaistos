import { Test, TestingModule } from '@nestjs/testing'
import { RoleController } from './app/role.controller'
import { RoleService } from './app/role.service'
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

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Role])],
            controllers: [RoleController],
            providers: [RoleService, RoleRepository],
        }).compile()

        createRoleDto = [{ name: 'admin' }, { name: 'employee' }]
        roleController = module.get<RoleController>(RoleController)
        roleService = module.get<RoleService>(RoleService)
    })

    describe('Controller', () => {
        it('RoleController est defini', () => {
            expect(roleController).toBeDefined()
        })

        it('RoleController.createRole', async () => {
            const result = await roleController.createRole(createRoleDto[0])

            expect(result.name).toEqual('admin')
        })

        it('RoleController.getRoles retourne tous les rôles', async () => {
            await roleController.createRole(createRoleDto[0])
            await roleController.createRole(createRoleDto[1])

            const result = await roleController.getRoles([])
            expect(result).toHaveLength(2)
            expect(result[0].name).toEqual('admin')
            expect(result[1].name).toEqual('employee')
        })

        it('RoleController.getRoles retourne un rôle existant', async () => {
            const createdRole = await roleController.createRole(createRoleDto[0])
            const result = await roleController.getRoles([createdRole.id])

            expect(result).toBeDefined()
            expect(result[0].id).toEqual(createdRole.id)
            expect(result[0].name).toEqual('admin')
        })

        it('RoleController.getRoles retourne une erreur avec un rôle inexistant', async () => {
            await expect(roleController.getRoles([999])).rejects.toThrow(NotFoundException)
        })

        it('RoleController.deleteRole supprime un rôle existant', async () => {
            const createdRole = await roleController.createRole(createRoleDto[0])
            await roleController.deleteRoles([createdRole.id])

            await expect(roleService.getRoles([createdRole.id])).rejects.toThrow(NotFoundException)
        })

        it("RoleController.deleteRoles retourne une erreur lors de la suppression d'un rôle inexistant", async () => {
            await expect(roleController.deleteRoles([999])).rejects.toThrow(NotFoundException)
        })

        it('RoleController.deleteRoles supprime plusieurs rôles existans', async () => {
            const createdRole1 = await roleController.createRole(createRoleDto[0])
            const createdRole2 = await roleController.createRole(createRoleDto[1])

            await roleController.deleteRoles([createdRole1.id, createdRole2.id])
            const result = await roleService.getRoles([])

            expect(result).toHaveLength(0)
        })

        it('RoleController.deleteRoles retourne une erreur lors de la suppression de plusieurs rôles inexistants', async () => {
            await expect(roleController.deleteRoles([1111, 2222])).rejects.toThrow(NotFoundException)
        })
    })

    describe('Service', () => {
        it('RoleService est defini', () => {
            expect(roleService).toBeDefined()
        })

        it('RoleService.createRole crée un rôle avec succès', async () => {
            const result = await roleService.createRole(createRoleDto[0])

            expect(result.name).toEqual('admin')
        })

        it('RoleService.getRoles retourne un rôle existant', async () => {
            const createdRole = await roleService.createRole(createRoleDto[0])
            const result = await roleService.getRoles([createdRole.id])

            expect(result).toBeDefined()
            expect(result[0].id).toEqual(createdRole.id)
            expect(result[0].name).toEqual('admin')
        })

        it('RoleService.getRoles retourne une erreur avec un rôle inexistant', async () => {
            await expect(roleService.getRoles([999])).rejects.toThrow(NotFoundException)
        })

        it('RoleService.getRoles supprime plusieurs rôles existants', async () => {
            const createdRole1 = await roleService.createRole(createRoleDto[0])
            const createdRole2 = await roleService.createRole(createRoleDto[1])
            const result = await roleService.getRoles([createdRole1.id, createdRole2.id])

            expect(result).toHaveLength(2)
            expect(result[0].name).toEqual('admin')
            expect(result[1].name).toEqual('employee')
        })

        it('RoleService.getRoles retourne tous les rôles avec un tableau vide', async () => {
            await roleService.createRole(createRoleDto[0])
            await roleService.createRole(createRoleDto[1])
            const result = await roleService.getRoles([])

            expect(result).toHaveLength(2)
        })

        it('RoleService.deleteRoles supprimme un rôle existant', async () => {
            const createdRole = await roleService.createRole(createRoleDto[0])
            await roleService.deleteRoles([createdRole.id])

            await expect(roleService.getRoles([])).resolves.toHaveLength(0)
        })

        it('RoleService.deleteRoles retourne une erreur avec un rôle inexistant', async () => {
            await expect(roleService.deleteRoles([999])).rejects.toThrow(NotFoundException)
        })

        it('RoleService.deleteRoles supprime plusieurs rôles', async () => {
            const createdRole1 = await roleService.createRole(createRoleDto[0])
            const createdRole2 = await roleService.createRole(createRoleDto[1])
            await roleService.deleteRoles([createdRole1.id, createdRole2.id])

            await expect(roleService.getRoles([])).resolves.toHaveLength(0)
        })

        it('RoleService.deleteRoles retourne une erreur avec des rôles inexistants', async () => {
            await expect(roleService.deleteRoles([1111, 2222])).rejects.toThrow(NotFoundException)
        })
    })
})
