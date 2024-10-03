import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Role } from './domaine/role.entity'
import { RoleService } from './app/role.service'
import { RoleRepository } from './infra/role.repository'

describe('RoleModule', () => {
    let roleService: RoleService
    let roleRepository: RoleRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Role])],
            providers: [RoleService, RoleRepository],
        }).compile()

        roleService = module.get<RoleService>(RoleService)
        roleRepository = module.get<RoleRepository>(RoleRepository)

        await roleRepository.repository.clear()
    })

    describe('Service', () => {
        it('RoleService est défini', () => {
            expect(roleService).toBeDefined()
        })

        it('RoleRepository est défini', () => {
            expect(roleRepository).toBeDefined()
        })

        it('RoleService.getManyRoles avec aucun role', async () => {
            const roles = await roleService.getManyRoles()
            expect(roles).toEqual([])
        })

        it('RoleService.createRole crée un role avec succès', async () => {
            const roleCreated = await roleService.createRole('ADMIN')
            const roles = await roleService.getManyRoles()
            expect(roles).toContainEqual(roleCreated)
        })

        it('RoleService.createRole échoue avec un champ requis vide', async () => {
            await expect(roleService.createRole('')).rejects.toThrow()
        })

        it('RoleService.getOneRole récupère un role par son id', async () => {
            const roleCreated = await roleService.createRole('ADMIN')
            const foundRole = await roleService.getOneRole(roleCreated.id)
            expect(foundRole).toEqual(roleCreated)
        })

        it("RoleService.getOneRole cherche un role qui n'existe pas", async () => {
            const invalidRoleId = 1111
            await expect(roleService.getOneRole(invalidRoleId)).rejects.toThrowError(
                `Le role avec l'ID ${invalidRoleId} est introuvable.`
            )
        })

        it('RoleService.getManyRoles retourne les roles dont les id on été donnés', async () => {
            const role1 = await roleService.createRole('ADMIN')
            const role2 = await roleService.createRole('EMPLOYEE')

            const roles = await roleService.getManyRoles([role1.id, role2.id])
            expect(roles).toEqual([role1, role2])
        })

        it("RoleService.getManyRoles retourne tous les roles si aucun ID n'est fourni", async () => {
            const role1 = await roleService.createRole('ADMIN')
            const role2 = await roleService.createRole('EMPLOYEE')

            const roles = await roleService.getManyRoles()
            expect(roles).toEqual([role1, role2])
        })

        it('RoleService.deleteOneRole supprime un role avec succès', async () => {
            const roleCreated = await roleService.createRole('ADMIN')

            await roleService.deleteOneRole(roleCreated.id)
            const roles = await roleService.getManyRoles()
            expect(roles).toEqual([])
        })

        it("RoleService.deleteOneRole supprime un role qui n'existe pas", async () => {
            const invalidRoleId = 1111
            await expect(roleService.deleteOneRole(invalidRoleId)).rejects.toThrowError(
                `Le role avec l'ID ${invalidRoleId} est introuvable.`
            )
        })

        it('RoleService.deleteManyRoles supprime plusieurs roles avec succès', async () => {
            const role1 = await roleService.createRole('USER')
            const role2 = await roleService.createRole('ADMIN')

            await roleService.deleteManyRoles([role1.id, role2.id])
            const roles = await roleService.getManyRoles()
            expect(roles).toEqual([])
        })
    })
})
