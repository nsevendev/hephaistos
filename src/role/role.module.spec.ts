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
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Role]),
            ],
            providers: [RoleService, RoleRepository],
        }).compile()

        roleService = module.get<RoleService>(RoleService)
        roleRepository = module.get<RoleRepository>(RoleRepository)
    })

    describe('Service', () => {
        it('RoleService est defini', () => {
            expect(roleService).toBeDefined()
        })

        it('RoleService.getRoles avec aucun role', async () => {
            const roles = await roleService.getRoles()
            expect(roles).toEqual([])
        })

        it('RoleService.getRoles avec role', async () => {
            const roleCreated = await roleService.createRole()
            const roles = await roleService.getRoles()
            expect(roles).toEqual([roleCreated])
        })
    })
})
