import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { MechanicalService } from './domaine/mechanical-service.entity'
import { MechanicalServiceService } from './app/mechanical-service.service'
import { MechanicalServiceRepository } from './infra/mechanical-service.repository'

describe('MechanicalServiceModule', () => {
    let mechanicalServiceService: MechanicalServiceService
    let mechanicalServiceRepository: MechanicalServiceRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([MechanicalService]),
            ],
            providers: [MechanicalServiceService, MechanicalServiceRepository],
        }).compile()

        mechanicalServiceService = module.get<MechanicalServiceService>(MechanicalServiceService)
        mechanicalServiceRepository = module.get<MechanicalServiceRepository>(MechanicalServiceRepository)
    })

    describe('Service', () => {
        it('MechanicalServiceService est defini', () => {
            expect(mechanicalServiceService).toBeDefined()
        })

        it('MechanicalServiceService.getMechanicalServices avec aucun service mécanique', async () => {
            const mechanicalServices = await mechanicalServiceService.getMechanicalServices()
            expect(mechanicalServices).toEqual([])
        })

        it('MechanicalServiceService.getMechanicalServices avec des services mécanique', async () => {
            const mechanicalServiceCreated = await mechanicalServiceService.createMechanicalService()
            const mechanicalServices = await mechanicalServiceService.getMechanicalServices()
            expect(mechanicalServices).toEqual([mechanicalServiceCreated])
        })
    })
})
