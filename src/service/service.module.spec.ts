import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Service } from './domaine/service.entity'
import { ServiceService } from './app/service.service'
import { ServiceRepository } from './infra/service.repository'

describe('ServiceModule', () => {
    let serviceService: ServiceService
    let serviceRepository: ServiceRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation BDD pour les tests
                TypeOrmModule.forFeature([Service]),
            ],
            providers: [ServiceService, ServiceRepository],
        }).compile()

        serviceService = module.get<ServiceService>(ServiceService)
        serviceRepository = module.get<ServiceRepository>(ServiceRepository)
    })

    describe('Service', () => {
        it('ServiceService est défini', () => {
            expect(serviceService).toBeDefined()
        })

        it('ServiceService.getServices avec aucun service', async () => {
            const services = await serviceService.getServices()
            expect(services).toEqual([])
        })

        it('ServiceService.getServices avec un service créé', async () => {
            const serviceCreated = await serviceService.createService({ name: 'test', createdBy: 1 })
            const services = await serviceService.getServices()
            expect(services).toEqual([serviceCreated])
        })

        it('ServiceService.createService crée un service avec succès', async () => {
            const serviceCreated = await serviceService.createService({ name: 'test', createdBy: 1 })
            const services = await serviceService.getServices()
            expect(services).toContain(serviceCreated)
        })

        it('ServiceService.deleteService supprime un service avec succès', async () => {
            const serviceCreated = await serviceService.createService({
                name: 'test',
                createdBy: 1,
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
            const service1 = await serviceService.createService({ name: 'test1', createdBy: 1 })
            const service2 = await serviceService.createService({ name: 'test2', createdBy: 1 })
            const services = await serviceService.getServices()
            expect(services).toEqual([service1, service2]) // Vérifie que les deux services sont présents
        })

        it('ServiceService.createService crée des services uniques', async () => {
            const service1 = await serviceService.createService({ name: 'test1', createdBy: 1 })
            const service2 = await serviceService.createService({ name: 'test2', createdBy: 1 })
            expect(service1.id).not.toEqual(service2.id) // Vérifie que les IDs des services sont uniques
        })
    })
})
