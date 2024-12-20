import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    BadRequestException,
    Query,
} from '@nestjs/common'
import { CarForSaleImageService } from './car-for-sale-image.service'
import { CreateCarForSaleImageDto } from './create-car-for-sale-image.dto'
import { ApiResponse, ApiTags, ApiBody, ApiQuery } from '@nestjs/swagger'
import { CarForSaleImage } from '../domaine/car-for-sale-image.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'
import { AwsS3Service } from '../../aws/app/aws.service'
import { z } from 'zod'

@ApiTags('Car For Sale Image Controller')
@Controller('car-for-sale-image')
export class CarForSaleImageController {
    constructor(
        private readonly carForSaleImageService: CarForSaleImageService,
        private readonly awsS3Service: AwsS3Service
    ) {}

    @Post()
    @ApiBody({
        type: [CreateCarForSaleImageDto],
        description: 'Données nécessaires pour ajouter des images de voiture à vendre',
    })
    @ApiResponse({ status: 201, description: 'Images ajoutées avec succès', type: [CarForSaleImage] })
    async addImages(@Body() createCarForSaleImageDtos: CreateCarForSaleImageDto[]) {
        const uploadedImages = []

        for (const dto of createCarForSaleImageDtos) {
            const fileSchemaValidation = z
                .object({
                    buffer: z.instanceof(Buffer).refine((buffer) => buffer.byteLength > 0, {
                        message: 'Le buffer ne peut pas être vide',
                    }),
                    originalname: z.string().min(1, { message: 'Le nom de fichier est requis' }),
                    mimetype: z.string().min(1, { message: 'Le type MIME est requis' }),
                    size: z.number().positive({ message: 'La taille doit être positive' }),
                })
                .parse(dto)

            const { fileKey } = await this.awsS3Service.uploadFile({ file: fileSchemaValidation })
            const fileUrl = await this.awsS3Service.getFileUrl({ fileKey })

            uploadedImages.push({
                ...dto,
                aws_key: fileKey,
                url: fileUrl,
            })
        }

        return this.carForSaleImageService.addImages(uploadedImages)
    }

    @Get()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs des images à rechercher',
        required: false,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie toutes les images ou celles correspondant aux IDs fournis',
        type: [CarForSaleImage],
    })
    async getImages(@Query('ids') ids: number[]) {
        const images = await this.carForSaleImageService.getImages(ids)

        for (const image of images) {
            image.url = await this.awsS3Service.getFileUrl({ fileKey: image.aws_key })
        }

        return images
    }

    @Get('car-for-sale/:carForSaleId')
    @ApiResponse({
        status: 200,
        description: 'Renvoie toutes les images pour une voiture spécifique',
        type: [CarForSaleImage],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune image trouvée pour la voiture spécifiée`,
    })
    async getImagesByCarForSale(@Param('carForSaleId') carForSaleId: number) {
        const images = await this.carForSaleImageService.getImagesByCarForSale(carForSaleId)

        for (const image of images) {
            image.url = await this.awsS3Service.getFileUrl({ fileKey: image.aws_key })
        }

        return images
    }

    @Delete()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs des images à supprimer',
        required: true,
    })
    @ApiResponse({ status: 204, description: 'Images supprimées avec succès' })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni ou tableau d'IDs vide`,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune image trouvée avec les IDs spécifiés`,
    })
    async deleteImages(@Query('ids') ids: number[]) {
        if (!ids || ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }
        const images = await this.carForSaleImageService.getImages(ids)

        for (const image of images) {
            await this.awsS3Service.deleteFile({ fileKey: image.aws_key })
        }

        return this.carForSaleImageService.deleteImages(ids)
    }
}
