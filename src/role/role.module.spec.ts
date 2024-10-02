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
            imports: [
                DatabaseTestModule, // Utilisation BDD pour les tests
                TypeOrmModule.forFeature([Role]),
            ],
            providers: [RoleService, RoleRepository],
        }).compile()

        roleService = module.get<RoleService>(RoleService)
        roleRepository = module.get<RoleRepository>(RoleRepository)
    })

    describe('Service', () => {
        it('RoleService est défini', () => {
            expect(roleService).toBeDefined()
        })

        it('RoleService.getRoles avec aucun rôle', async () => {
            const roles = await roleService.getRoles()
            expect(roles).toEqual([])
        })

        it('RoleService.getRoles avec un rôle créé', async () => {
            const roleCreated = await roleService.createRole()
            const roles = await roleService.getRoles()
            expect(roles).toEqual([roleCreated])
        })

        it('RoleService.createRole crée un rôle avec succès', async () => {
            const roleCreated = await roleService.createRole()
            const roles = await roleService.getRoles()
            expect(roles).toContain(roleCreated)
        })

        it('RoleService.deleteRole supprime un rôle avec succès', async () => {
            const roleCreated = await roleService.createRole()
            await roleService.deleteRole(roleCreated.id)
            const roles = await roleService.getRoles()
            expect(roles).toEqual([])
        })

        it("RoleService.deleteRole lève une erreur si le rôle n'existe pas", async () => {
            const invalidId = 9999
            await expect(roleService.deleteRole(invalidId)).rejects.toThrowError(
                `Le rôle avec l'ID ${invalidId} n'a pas été trouvé.`
            )
        })

        it('RoleService.getRoles retourne plusieurs rôles', async () => {
            const role1 = await roleService.createRole()
            const role2 = await roleService.createRole()
            const roles = await roleService.getRoles()
            expect(roles).toEqual([role1, role2])
        })

        it('RoleService.createRole crée des rôles uniques', async () => {
            const role1 = await roleService.createRole()
            const role2 = await roleService.createRole()
            expect(role1.id).not.toEqual(role2.id)
        })
    })
})
