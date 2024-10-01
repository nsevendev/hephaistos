import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Admin } from './domaine/admin.entity'
import { AdminService } from './app/admin.service'
import { AdminRepository } from './infra/admin.repository'

describe('AdminModule', () => {
    let adminService: AdminService
    let adminRepository: AdminRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Admin]),
            ],
            providers: [AdminService, AdminRepository],
        }).compile()

        adminService = module.get<AdminService>(AdminService)
        adminRepository = module.get<AdminRepository>(AdminRepository)
    })

    describe('Service', () => {
        it('AdminService est defini', () => {
            expect(adminService).toBeDefined()
        })

        it('AdminService.getAdmins avec aucun admin', async () => {
            const admins = await adminService.getAdmins()
            expect(admins).toEqual([])
        })

        it('AdminService.getAdmins avec admin', async () => {
            const adminCreated = await adminService.createAdmin()
            const admins = await adminService.getAdmins()
            expect(admins).toEqual([adminCreated])
        })
    })
})
