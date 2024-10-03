import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Admin } from './domaine/admin.entity'
import { AdminService } from './app/admin.service'
import { AdminRepository } from './infra/admin.repository'
import { RoleRepository } from '../role/infra/role.repository'
import { Role } from '../role/domaine/role.entity'

describe('AdminModule', () => {
    let adminService: AdminService
    let adminRepository: AdminRepository
    let roleRepository: RoleRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Admin, Role])],
            providers: [AdminService, AdminRepository, RoleRepository],
        }).compile()

        adminService = module.get<AdminService>(AdminService)
        adminRepository = module.get<AdminRepository>(AdminRepository)
        roleRepository = module.get<RoleRepository>(RoleRepository)

        await adminRepository.repository.clear()
        await roleRepository.repository.clear()
    })

    describe('AdminService', () => {
        it('AdminService est défini', () => {
            expect(adminService).toBeDefined()
        })

        it('AdminService.getAdmins avec aucun admin', async () => {
            const admins = await adminService.getAdmins()
            expect(admins).toEqual([])
        })

        it('AdminService.createAdmin crée un admin avec succès', async () => {
            const role = await roleRepository.repository.save({ name: 'admin' })

            const adminData = {
                username: 'admin1',
                email: 'admin1@example.com',
                password: 'password123',
                roleId: role.id,
            }
            const adminCreated = await adminService.createAdmin(adminData)
            const admins = await adminService.getAdmins()
            expect(admins).toContainEqual(adminCreated)
        })

        it('AdminService.getAdmin récupère un admin par ID', async () => {
            const role = await roleRepository.repository.save({ name: 'admin' })

            const adminData = {
                username: 'admin1',
                email: 'admin1@example.com',
                password: 'password123',
                roleId: role.id,
            }
            const adminCreated = await adminService.createAdmin(adminData)
            const adminRetrieved = await adminService.getAdmin(adminCreated.id)
            expect(adminRetrieved).toEqual(adminCreated)
        })

        it("AdminService.updateAdmin met à jour les informations de l'admin", async () => {
            const role = await roleRepository.repository.save({ name: 'admin' })

            const adminData = {
                username: 'admin1',
                email: 'admin1@example.com',
                password: 'password123',
                roleId: role.id,
            }
            const adminCreated = await adminService.createAdmin(adminData)

            const updatedData = { username: 'admin1_updated' }
            const updatedAdmin = await adminService.updateAdmin(adminCreated.id, updatedData)
            expect(updatedAdmin.username).toBe('admin1_updated')
        })

        it('AdminService.deleteAdmin supprime un admin avec succès', async () => {
            const role = await roleRepository.repository.save({ name: 'admin' })

            const adminData = {
                username: 'admin1',
                email: 'admin1@example.com',
                password: 'password123',
                roleId: role.id,
            }
            const adminCreated = await adminService.createAdmin(adminData)
            await adminService.deleteAdmin(adminCreated.id)
            const admins = await adminService.getAdmins()
            expect(admins).toEqual([])
        })

        it("AdminService.deleteAdmin retourne une erreur si l'admin n'existe pas", async () => {
            const invalidAdminId = 1111
            await expect(adminService.deleteAdmin(invalidAdminId)).rejects.toThrowError(
                `L'admin avec l'ID ${invalidAdminId} est introuvable.`
            )
        })
    })
})
