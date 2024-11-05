import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { InterfaceImageService } from './app/interface-image.service'
import { InterfaceImageRepository } from './infra/interface-image.repository'
import { InterfaceImageController } from './app/interface-image.controller'
import { AwsS3Service } from '../aws/app/aws.service'
import { InterfaceImage } from './domaine/interface-image.entity'

describe('InterfaceImageService', () => {
    let interfaceImageService: InterfaceImageService
    let interfaceImageRepository: InterfaceImageRepository
    let interfaceImageController: InterfaceImageController
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([InterfaceImage])],
            providers: [InterfaceImageService, InterfaceImageRepository, AwsS3Service],
            controllers: [InterfaceImageController],
        }).compile()

        interfaceImageService = module.get<InterfaceImageService>(InterfaceImageService)
        interfaceImageRepository = module.get<InterfaceImageRepository>(InterfaceImageRepository)
        interfaceImageController = module.get<InterfaceImageController>(InterfaceImageController)
    })

    describe('Service', () => {
        it('InterfaceImageService est défini', () => {
            expect(interfaceImageService).toBeDefined()
        })

        it('InterfaceImageRepository est défini', () => {
            expect(interfaceImageRepository).toBeDefined()
        })
    })

    describe('Controller', () => {
        it('InterfaceImageController est défini', () => {
            expect(interfaceImageController).toBeDefined()
        })
    })
})
