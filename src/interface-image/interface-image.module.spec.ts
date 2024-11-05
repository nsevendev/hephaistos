import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { InterfaceImageService } from './app/interface-image.service'
import { InterfaceImageRepository } from './infra/interface-image.repository'
import { InterfaceImageController } from './app/interface-image.controller'
import { AwsS3Service } from '../aws/app/aws.service'
import { InterfaceImage } from './domaine/interface-image.entity'
import { CreateInterfaceImageDto } from './app/create-interface-image.dto'
import { UpdateInterfaceImageDto } from './app/update-interface-image.dto'
import { NotFoundException } from '@nestjs/common'

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
        it("doit définir l'InterfaceImageService", () => {
            expect(interfaceImageService).toBeDefined()
        })

        it("doit définir l'InterfaceImageRepository", () => {
            expect(interfaceImageRepository).toBeDefined()
        })

        it('ajoute une nouvelle image avec succès', async () => {
            const imageDto: CreateInterfaceImageDto = {
                url: 'https://example.com/image.jpg',
                aws_key: 'aws-key-1',
            }

            const result = await interfaceImageService.addInterfaceImage(imageDto)

            expect(result).toBeDefined()
            expect(result.url).toEqual(imageDto.url)
        })

        it('met à jour une image existante avec succès', async () => {
            const createDto: CreateInterfaceImageDto = {
                url: 'https://example.com/image.jpg',
                aws_key: 'aws-key-1',
            }
            const createdImage = await interfaceImageService.addInterfaceImage(createDto)

            const updateDto: UpdateInterfaceImageDto = {
                url: 'https://example.com/updated-image.jpg',
            }

            const updatedImage = await interfaceImageService.updateInterfaceImage(createdImage.id, updateDto)
            expect(updatedImage.url).toEqual(updateDto.url)
        })

        it("lance une NotFoundException si l'image à mettre à jour n'est pas trouvée", async () => {
            const updateDto: UpdateInterfaceImageDto = {
                url: 'https://example.com/nonexistent-image.jpg',
            }
            await expect(interfaceImageService.updateInterfaceImage(9999, updateDto)).rejects.toThrow(
                NotFoundException
            )
        })

        it('récupère les images avec des IDs spécifiques', async () => {
            const imageDto: CreateInterfaceImageDto = {
                url: 'https://example.com/image.jpg',
                aws_key: 'aws-key-1',
            }
            const createdImage = await interfaceImageService.addInterfaceImage(imageDto)

            const result = await interfaceImageService.getInterfacesImages([createdImage.id])
            expect(result.images[0].id).toEqual(createdImage.id)
        })

        it('doit renvoyer un notFoundCount supérieur à 0 si les IDs fournis ne sont pas valides', async () => {
            const result = await interfaceImageService.getInterfacesImages([999])
            expect(result.notFoundCount).toBeGreaterThan(0)
        })

        it('supprime des images avec succès', async () => {
            const imageDto: CreateInterfaceImageDto = {
                url: 'https://example.com/image.jpg',
                aws_key: 'aws-key-1',
            }
            const createdImage = await interfaceImageService.addInterfaceImage(imageDto)

            const deleteResult = await interfaceImageService.deleteInterfacesImages([createdImage.id])
            expect(deleteResult.deletedCount).toBe(1)
        })

        it('lance une NotFoundException si les images à supprimer ne sont pas trouvées', async () => {
            await expect(interfaceImageService.deleteInterfacesImages([9999])).rejects.toThrow(
                NotFoundException
            )
        })
    })

    describe('Controller', () => {
        it('InterfaceImageController est défini', () => {
            expect(interfaceImageController).toBeDefined()
        })
    })
})
