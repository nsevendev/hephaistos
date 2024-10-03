import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Service } from './domaine/service.entity'
import { ServiceService } from './app/service.service'
import { ServiceRepository } from './infra/service.repository'
import { Admin } from '../admin/domaine/admin.entity'
import { AdminRepository } from '../admin/infra/admin.repository'

describe('ServiceModule', () => {
    let serviceService: ServiceService
    let serviceRepository: ServiceRepository
    let adminRepository: AdminRepository // Déclaration du repository Admin
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation BDD pour les tests
                TypeOrmModule.forFeature([Service, Admin]), // Ajouter Admin ici
            ],
            providers: [ServiceService, ServiceRepository, AdminRepository], // Inclure AdminRepository
        }).compile()

        serviceService = module.get<ServiceService>(ServiceService)
        serviceRepository = module.get<ServiceRepository>(ServiceRepository)
        adminRepository = module.get<AdminRepository>(AdminRepository) // Initialisation du repository Admin

        // Effacer les données avant chaque test
        await serviceRepository.repository.clear()
        await adminRepository.repository.clear() // Effacer aussi les admins
    })

    describe('Service', () => {
        it('ServiceService est défini', () => {
            expect(serviceService).toBeDefined()
        })

        it('ServiceRepository est défini', () => {
            expect(ServiceRepository).toBeDefined()
        })

        it('AdminRepository est défini', () => {
            expect(AdminRepository).toBeDefined() // Vérifier que le repository Admin est défini
        })

        it('ServiceService.getServices avec aucun service', async () => {
            const services = await serviceService.getServices()
            expect(services).toEqual([])
        })

        it('ServiceService.getServices avec un service créé', async () => {
            // Créer un admin avant de créer un service
            const adminCreated = await adminRepository.repository.save({ name: 'Admin Test' })

            const serviceCreated = await serviceService.createService({
                name: 'test',
                createdBy: adminCreated.id,
            })
            const services = await serviceService.getServices()
            expect(services).toEqual([serviceCreated])
        })

        it('ServiceService.createService crée un service avec succès', async () => {
            const adminCreated = await adminRepository.repository.save({ name: 'Admin Test' })

            const serviceCreated = await serviceService.createService({
                name: 'test',
                createdBy: adminCreated.id,
            })
            const services = await serviceService.getServices()
            expect(services).toContain(serviceCreated)
        })

        it("ServiceService.createService lève une erreur si l'admin n'existe pas", async () => {
            // Utiliser un ID d'admin qui n'existe pas
            const invalidAdminId = 9999

            await expect(
                serviceService.createService({ name: 'test', createdBy: invalidAdminId })
            ).rejects.toThrowError(`L'admin avec l'ID ${invalidAdminId} n'existe pas.`)
        })

        it('ServiceService.deleteService supprime un service avec succès', async () => {
            const adminCreated = await adminRepository.repository.save({ name: 'Admin Test' })

            const serviceCreated = await serviceService.createService({
                name: 'test',
                createdBy: adminCreated.id,
            })
            await serviceService.deleteService(serviceCreated.id) // Suppression du service
            const services = await serviceService.getServices()
            expect(services).toEqual([]) // Aucun service ne devrait rester après la suppression
        })

        it("ServiceService.deleteService lève une erreur si le service n'existe pas", async () => {
            const invalidServiceId = 1111 // Un ID qui n'existe pas
            await expect(serviceService.deleteService(invalidServiceId)).rejects.toThrowError(
                `Le service avec l'ID ${invalidServiceId} n'a pas été trouvé.`
            )
        })

        it('ServiceService.getServices retourne plusieurs services', async () => {
            const adminCreated = await adminRepository.repository.save({ name: 'Admin Test' })
            const service1 = await serviceService.createService({ name: 'test1', createdBy: adminCreated.id })
            const service2 = await serviceService.createService({ name: 'test2', createdBy: adminCreated.id })
            const services = await serviceService.getServices()
            expect(services).toEqual([service1, service2]) // Vérifie que les deux services sont présents
        })

        it('ServiceService.createService crée des services uniques', async () => {
            const adminCreated = await adminRepository.repository.save({ name: 'Admin Test' })
            const service1 = await serviceService.createService({ name: 'test1', createdBy: adminCreated.id })
            const service2 = await serviceService.createService({ name: 'test2', createdBy: adminCreated.id })
            expect(service1.id).not.toEqual(service2.id) // Vérifie que les IDs des services sont uniques
        })
    })
})
