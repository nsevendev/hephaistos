import { Test, TestingModule } from '@nestjs/testing'
import { InterfaceHomeController } from './app/interface-home.controller'
import { InterfaceHomeService } from './app/interface-home.service'
import { InterfaceHomeRepository } from './infra/interface-home.repository'
import { InterfaceImageService } from './../interface-image/app/interface-image.service'
import { CreateInterfaceHomeDto } from './app/create-interface-home.dto'
import { UpdateInterfaceHomeDto } from './app/update-interface-home.dto'
import { NotFoundException, BadRequestException } from '@nestjs/common'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { InterfaceHome } from './domaine/interface-home.entity'
import { InterfaceImage } from '../interface-image/domaine/interface-image.entity'
import { CreateInterfaceImageDto } from '../interface-image/app/create-interface-image.dto'
import { InterfaceImageModule } from '../interface-image/interface-image.module'
import { AwsS3Service } from '../aws/app/aws.service'
import { AwsServiceModule } from '../aws/aws.module'

describe('InterfaceHome', () => {
    let interfaceHomeService: InterfaceHomeService
    let interfaceHomeController: InterfaceHomeController
    let interfaceImageService: InterfaceImageService
    let awsS3Service: AwsS3Service
    let createdImage: InterfaceImage
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule,
                TypeOrmModule.forFeature([InterfaceHome]),
                InterfaceImageModule,
                AwsServiceModule,
            ],
            providers: [InterfaceHomeService, InterfaceHomeRepository, InterfaceImageService],
            controllers: [InterfaceHomeController],
        }).compile()

        interfaceHomeService = module.get<InterfaceHomeService>(InterfaceHomeService)
        interfaceHomeController = module.get<InterfaceHomeController>(InterfaceHomeController)
        interfaceImageService = module.get<InterfaceImageService>(InterfaceImageService)
        awsS3Service = module.get<AwsS3Service>(AwsS3Service)

        const file = {
            size: 12345,
            buffer: Buffer.from('fake-image-content'),
            originalname: 'test-image.jpg',
            mimetype: 'image/jpeg',
        }
        const awsKey = await awsS3Service.uploadFile({ file })
        const imageUrl = await awsS3Service.getFileUrl(awsKey)
        createdImage = await interfaceImageService.addInterfaceImage({
            url: imageUrl,
            aws_key: awsKey.fileKey,
        })
        console.log('awskey : ' + createdImage.aws_key)
        console.log('created_at : ' + createdImage.created_at)
        console.log('id : ' + createdImage.id)
        console.log('url : ' + createdImage.url)
        const test = await interfaceImageService.getInterfacesImages([createdImage.id])
        console.log('image : ' + JSON.stringify(test.images[0], null, 2))
        console.log('imagenotfound : ' + test.notFoundCount)
    })

    afterAll(async () => {
        await interfaceImageService.deleteInterfacesImages([createdImage.id])
    })

    describe('Service', () => {
        it('InterfaceHomeService est défini', () => {
            expect(interfaceHomeService).toBeDefined()
        })

        it("crée l'interface home avec succès", async () => {
            console.log(createdImage)
            const createDto: CreateInterfaceHomeDto = {
                page_title: 'Page Test',
                page_text: 'Ceci est un test.',
                section1_title: 'Section 1 Test',
                section1_image: createdImage.id,
                section1_button_text: 'Section 1 Button',
                section2_title: 'Section 2 Test',
                section2_image: createdImage.id,
                section2_button_text: 'Section 2 Button',
                section3_title: 'Section 3 Test',
                section3_image: createdImage.id,
                section3_button_text: 'Section 3 Button',
            }

            const result = await interfaceHomeService.addInterfaceHome(createDto)
            expect(result).toBeDefined()
            expect(result.page_title).toBe(createDto.page_title)
            expect(result.section1_image.id).toBe(createDto.section1_image)
            expect(result.section2_image.id).toBe(createDto.section2_image)
            expect(result.section3_image.id).toBe(createDto.section3_image)

            await interfaceHomeService.deleteInterfaceHome(result.id)
        })

        it('retourne une erreur si des images sont introuvables lors de la création', async () => {
            const createDto: CreateInterfaceHomeDto = {
                page_title: 'Page Invalid Test',
                page_text: 'Ceci est un test.',
                section1_title: 'Section 1 Test',
                section1_image: 9999,
                section1_button_text: 'Section 1 Button',
                section2_title: 'Section 2 Test',
                section2_image: createdImage.id,
                section2_button_text: 'Section 2 Button',
                section3_title: 'Section 3 Test',
                section3_image: createdImage.id,
                section3_button_text: 'Section 3 Button',
            }

            await expect(interfaceHomeService.addInterfaceHome(createDto)).rejects.toThrow(
                BadRequestException
            )
        })

        it("récupère l'interface home avec succès", async () => {
            const createDto: CreateInterfaceHomeDto = {
                page_title: 'Page Retrieve Test',
                page_text: 'Ceci est un test.',
                section1_title: 'Section 1 Test',
                section1_image: createdImage.id,
                section1_button_text: 'Section 1 Button',
                section2_title: 'Section 2 Test',
                section2_image: createdImage.id,
                section2_button_text: 'Section 2 Button',
                section3_title: 'Section 3 Test',
                section3_image: createdImage.id,
                section3_button_text: 'Section 3 Button',
            }

            const createdHome = await interfaceHomeService.addInterfaceHome(createDto)
            const retrievedHome = await interfaceHomeService.getInterfaceHome()

            expect(retrievedHome).toBeDefined()
            expect(retrievedHome.id).toBe(createdHome.id)
            expect(retrievedHome.page_title).toBe(createdHome.page_title)

            await interfaceHomeService.deleteInterfaceHome(createdHome.id)
        })

        it("retourne une erreur si aucune interface home n'est trouvée", async () => {
            await expect(interfaceHomeService.getInterfaceHome()).rejects.toThrow(NotFoundException)
        })

        it("met à jour l'interface home avec succès", async () => {
            const createDto: CreateInterfaceHomeDto = {
                page_title: 'Page Before Update',
                page_text: 'Ceci est un test.',
                section1_title: 'Section 1 Before Update',
                section1_image: createdImage.id,
                section1_button_text: 'Section 1 Button',
                section2_title: 'Section 2 Before Update',
                section2_image: createdImage.id,
                section2_button_text: 'Section 2 Button',
                section3_title: 'Section 3 Before Update',
                section3_image: createdImage.id,
                section3_button_text: 'Section 3 Button',
            }

            const createdHome = await interfaceHomeService.addInterfaceHome(createDto)

            const updateDto: UpdateInterfaceHomeDto = {
                page_title: 'Page After Update',
                section1_title: 'Section 1 After Update',
            }

            const updatedHome = await interfaceHomeService.updateInterfaceHome(createdHome.id, updateDto)
            expect(updatedHome).toBeDefined()
            expect(updatedHome.page_title).toBe(updateDto.page_title)
            expect(updatedHome.section1_title).toBe(updateDto.section1_title)

            await interfaceHomeService.deleteInterfaceHome(updatedHome.id)
        })

        it("retourne une erreur si l'interface home à mettre à jour n'existe pas", async () => {
            const updateDto: UpdateInterfaceHomeDto = {
                page_title: 'Nonexistent Update',
            }

            await expect(interfaceHomeService.updateInterfaceHome(9999, updateDto)).rejects.toThrow(
                NotFoundException
            )
        })

        it('supprime une interface home avec succès', async () => {
            const createDto: CreateInterfaceHomeDto = {
                page_title: 'Page To Be Deleted',
                page_text: 'Ceci est un test.',
                section1_title: 'Section 1 Delete',
                section1_image: createdImage.id,
                section1_button_text: 'Section 1 Button',
                section2_title: 'Section 2 Delete',
                section2_image: createdImage.id,
                section2_button_text: 'Section 2 Button',
                section3_title: 'Section 3 Delete',
                section3_image: createdImage.id,
                section3_button_text: 'Section 3 Button',
            }

            const createdHome = await interfaceHomeService.addInterfaceHome(createDto)
            const result = await interfaceHomeService.deleteInterfaceHome(createdHome.id)

            expect(result).toBeDefined()
            expect(result.message).toContain(createdHome.id)
        })

        it("retourne une erreur si l'interface home à supprimer n'existe pas", async () => {
            await expect(interfaceHomeService.deleteInterfaceHome(9999)).rejects.toThrow(NotFoundException)
        })
    })

    describe('Controller', () => {
        it('InterfaceHomeController est défini', () => {
            expect(interfaceHomeController).toBeDefined()
        })
        /*
        it("crée l'interface home via le contrôleur", async () => {})

        it("récupère les données actuelles de l'interface home via le contrôleur", async () => {})

        it('met à jour une interface home via le contrôleur', async () => {})

        it('supprime une interface home via le contrôleur', async () => {})
        */
    })
})
