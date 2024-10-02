import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSale } from './domaine/car-for-sale.entity'
import { CarForSaleService } from './app/car-for-sale.service'
import { CarForSaleRepository } from './infra/car-for-sale.repository'

describe('CarForSaleModule', () => {
    let carForSaleService: CarForSaleService
    let carForSaleRepository: CarForSaleRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([CarForSale]),
            ],
            providers: [CarForSaleService, CarForSaleRepository],
        }).compile()

        carForSaleService = module.get<CarForSaleService>(CarForSaleService)
        carForSaleRepository = module.get<CarForSaleRepository>(CarForSaleRepository)
    })

    describe('Service', () => {
        it('CarForSaleService est defini', () => {
            expect(carForSaleService).toBeDefined()
        })

        it('CarForSaleService.getCarForSales avec aucune voiture a vendre', async () => {
            const carForSales = await carForSaleService.getCarForSales()
            expect(carForSales).toEqual([])
        })

        it('CarForSaleService.getCarForSales avec des voitures mise en vente', async () => {
            const carForSaleCreated = await carForSaleService.createCarForSale()
            const carForSales = await carForSaleService.getCarForSales()
            expect(carForSales).toEqual([carForSaleCreated])
        })
    })
})
