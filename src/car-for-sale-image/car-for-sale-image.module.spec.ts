import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { CarForSaleImageService } from './app/car-for-sale-image.service'
import { CarForSaleImageRepository } from './infra/car-for-sale-image.repository'
import { CarForSaleService } from '../car-for-sale/app/car-for-sale.service'
import { UserService } from '../user/app/user.service'
import { CreateCarForSaleImageDto } from './app/create-car-for-sale-image.dto'
import { BadRequestException, NotFoundException } from '@nestjs/common'
import { User } from '../user/domaine/user.entity'
import { CarForSale } from '../car-for-sale/domaine/car-for-sale.entity'
import { CarForSaleModule } from '../car-for-sale/car-for-sale.module'
import { UserModule } from '../user/user.module'
import { RoleModule } from '../role/role.module'
import { RoleService } from '../role/app/role.service'
import { Role } from '../role/domaine/role.entity'
import { CarForSaleImage } from './domaine/car-for-sale-image.entity'
import { CarForSaleImageController } from './app/car-for-sale-image.controller'
import { AwsS3Service } from './app/aws.service'
import { UploadCarForSaleImageDto } from './app/upload-car-for-sale-image.dto'
import { AWS_BUCKET_NAME } from './app/aws.service'

describe('CarForSaleImageService', () => {
    let carForSaleImageService: CarForSaleImageService
    let carForSaleImageRepository: CarForSaleImageRepository
    let carForSaleImageController: CarForSaleImageController
    let carForSaleService: CarForSaleService
    let userService: UserService
    let roleService: RoleService
    let roleCreated: Role
    let userCreated: User
    let carForSaleCreated: CarForSale
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule,
                TypeOrmModule.forFeature([CarForSaleImage]),
                CarForSaleModule,
                RoleModule,
                UserModule,
            ],
            providers: [CarForSaleImageService, CarForSaleImageRepository, AwsS3Service],
            controllers: [CarForSaleImageController],
        }).compile()

        carForSaleImageService = module.get<CarForSaleImageService>(CarForSaleImageService)
        carForSaleImageRepository = module.get<CarForSaleImageRepository>(CarForSaleImageRepository)
        carForSaleImageController = module.get<CarForSaleImageController>(CarForSaleImageController)
        roleService = module.get<RoleService>(RoleService)
        userService = module.get<UserService>(UserService)
        carForSaleService = module.get<CarForSaleService>(CarForSaleService)
    })

    beforeEach(async () => {
        roleCreated = await roleService.createRole({
            name: 'admin',
        })

        userCreated = await userService.createUser({
            username: 'testuser',
            email: 'test@example.com',
            password: 'hashedPassword',
            role: roleCreated.id,
        })

        carForSaleCreated = await carForSaleService.createCarForSale({
            manufacturer: 'Toyota',
            model: 'Corolla',
            price: 20000,
            power: 120,
            tax_power: 5,
            fuel: 'Gasoline',
            mileage: 5000,
            conveyance_type: 'Manual',
            color: 'Blue',
            created_by: userCreated.id,
        })
    })

    describe('Service', () => {
        it('CarForSaleImageService est défini', () => {
            expect(carForSaleImageService).toBeDefined()
        })

        it('CarForSaleImageRepository est défini', () => {
            expect(carForSaleImageRepository).toBeDefined()
        })

        it('ajoute des images avec succès', async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://images.pexels.com/photos/416160/pexels-photo-416160.jpeg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            const result = await carForSaleImageService.addImages(imageDto)

            expect(result).toBeDefined()
            expect(result[0].url).toEqual(imageDto[0].url)
        })

        it("retourne une erreur si la voiture n'est pas trouvée", async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: 999,
                    url: 'https://example.com/image.jpg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            await expect(carForSaleImageService.addImages(imageDto)).rejects.toThrow(BadRequestException)
        })

        it("retourne une erreur si l'utilisateur n'est pas trouvé", async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://example.com/image.jpg',
                    aws_key: 'aws-key-1',
                    created_by: 999,
                },
            ]

            await expect(carForSaleImageService.addImages(imageDto)).rejects.toThrow(BadRequestException)
        })

        it('récupère toutes les images si aucun ID n’est fourni', async () => {
            const result = await carForSaleImageService.getImages()
            expect(Array.isArray(result)).toBe(true)
        })

        it('récupère des images avec des IDs valides', async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://example.com/image.jpg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            const createdImages = await carForSaleImageService.addImages(imageDto)
            const result = await carForSaleImageService.getImages([createdImages[0].id])

            expect(result[0].id).toEqual(createdImages[0].id)
        })

        it("retourne une erreur si des IDs invalides d'images sont fournis", async () => {
            await expect(carForSaleImageService.getImages([9999])).rejects.toThrow(NotFoundException)
        })

        it('récupère des images par voiture en vente', async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://example.com/image.jpg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            await carForSaleImageService.addImages(imageDto)
            const result = await carForSaleImageService.getImagesByCarForSale(carForSaleCreated.id)

            expect(result).toBeDefined()
            expect(result[0].car_for_sale.id).toEqual(carForSaleCreated.id)
        })

        it("retourne une erreur si la voiture en vente n'est pas trouvée", async () => {
            await expect(carForSaleImageService.getImagesByCarForSale(999)).rejects.toThrow(NotFoundException)
        })

        /* it('supprime des images avec succès', async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://example.com/image.jpg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            const createdImages = await carForSaleImageService.addImages(imageDto)
            await carForSaleImageService.deleteImages([createdImages[0].id])

            const images = await carForSaleImageService.getImages()
            expect(images).not.toContainEqual(expect.objectContaining({ id: createdImages[0].id }))
        }) */

        it('retourne une erreur si les images à supprimer ne sont pas trouvées', async () => {
            await expect(carForSaleImageService.deleteImages([9999])).rejects.toThrow(NotFoundException)
        })
    })

    describe('Controller', () => {
        it('CarForSaleImageController est défini', () => {
            expect(carForSaleImageController).toBeDefined()
        })

        it('ajoute des images avec succès via le contrôleur', async () => {
            const mockFile = {
                buffer: Buffer.from('test content'),
                originalname: 'test-image.jpg',
                mimetype: 'image/jpeg',
                size: 1024,
            }

            const imageDto: UploadCarForSaleImageDto = {
                car_for_sale: carForSaleCreated.id,
                buffer: mockFile.buffer,
                originalname: mockFile.originalname,
                mimetype: mockFile.mimetype,
                size: mockFile.size,
                created_by: userCreated.id,
            }

            const result = await carForSaleImageController.addImages([imageDto])

            expect(result).toBeDefined()
            expect(result[0].url).toMatch(`https://${AWS_BUCKET_NAME}.s3.eu-central-1.amazonaws.com/`)
            expect(result[0].url).toContain(mockFile.originalname)
        })

        it('récupère toutes les images via le contrôleur', async () => {
            const result = await carForSaleImageController.getImages([])
            expect(Array.isArray(result)).toBe(true)
        })

        it('récupère des images par ID via le contrôleur', async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://images.pexels.com/photos/416160/pexels-photo-416160.jpeg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            const createdImages = await carForSaleImageService.addImages(imageDto)
            const result = await carForSaleImageController.getImages([createdImages[0].id])

            expect(result[0].id).toEqual(createdImages[0].id)
        })

        it('retourne une erreur si les images par ID ne sont pas trouvées', async () => {
            await expect(carForSaleImageController.getImages([9999])).rejects.toThrow(NotFoundException)
        })

        it('supprime des images via le contrôleur', async () => {
            const imageDto: CreateCarForSaleImageDto[] = [
                {
                    car_for_sale: carForSaleCreated.id,
                    url: 'https://images.pexels.com/photos/416160/pexels-photo-416160.jpeg',
                    aws_key: 'aws-key-1',
                    created_by: userCreated.id,
                },
            ]

            const createdImages = await carForSaleImageService.addImages(imageDto)
            await carForSaleImageController.deleteImages([createdImages[0].id])

            const images = await carForSaleImageService.getImages()
            expect(images).not.toContainEqual(expect.objectContaining({ id: createdImages[0].id }))
        })

        it('retourne une erreur si les images à supprimer ne sont pas trouvées via le contrôleur', async () => {
            await expect(carForSaleImageController.deleteImages([9999])).rejects.toThrow(NotFoundException)
        })
    })
})
