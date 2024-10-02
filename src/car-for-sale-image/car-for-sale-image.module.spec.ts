import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSaleImage } from './domaine/car-for-sale-image.entity'
import { CarForSaleImageService } from './app/car-for-sale-image.service'
import { CarForSaleImageRepository } from './infra/car-for-sale-image.repository'

describe('CarForSaleImageModule', () => {
    let carForSaleImageService: CarForSaleImageService
    let carForSaleImageRepository: CarForSaleImageRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([CarForSaleImage]),
            ],
            providers: [CarForSaleImageService, CarForSaleImageRepository],
        }).compile()

        carForSaleImageService = module.get<CarForSaleImageService>(CarForSaleImageService)
        carForSaleImageRepository = module.get<CarForSaleImageRepository>(CarForSaleImageRepository)
    })

    describe('Service', () => {
        it('CarForSaleImageService est defini', () => {
            expect(carForSaleImageService).toBeDefined()
        })

        it('CarForSaleImageService.getCarForSaleImages avec aucune image', async () => {
            const carForSaleImages = await carForSaleImageService.getCarForSaleImages()
            expect(carForSaleImages).toEqual([])
        })

        it('CarForSaleImageService.getCarForSaleImages avec des images', async () => {
            const carForSaleImageCreated = await carForSaleImageService.createCarForSaleImage()
            const carForSaleImages = await carForSaleImageService.getCarForSaleImages()
            expect(carForSaleImages).toEqual([carForSaleImageCreated])
        })
    })
})
