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
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Service]),
            ],
            providers: [ServiceService, ServiceRepository],
        }).compile()

        serviceService = module.get<ServiceService>(ServiceService)
        serviceRepository = module.get<ServiceRepository>(ServiceRepository)
    })

    describe('Service', () => {
        it('ServiceService est defini', () => {
            expect(serviceService).toBeDefined()
        })

        it('ServiceService.getServices avec aucun service', async () => {
            const services = await serviceService.getServices()
            expect(services).toEqual([])
        })

        it('ServiceService.getServices avec service', async () => {
            const serviceCreated = await serviceService.createService()
            const services = await serviceService.getServices()
            expect(services).toEqual([serviceCreated])
        })
    })
})
