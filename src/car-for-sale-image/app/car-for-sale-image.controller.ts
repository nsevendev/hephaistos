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
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { CarForSaleImage } from '../domaine/car-for-sale-image.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'
import { AwsS3Service } from './aws.service'
import { z } from 'zod'

@ApiTags('Car For Sale Image Controller')
@Controller('car-for-sale-image')
export class CarForSaleImageController {
    constructor(
        private readonly carForSaleImageService: CarForSaleImageService,
        private readonly awsS3Service: AwsS3Service
    ) {}

    @Post()
    @ApiResponse({ status: 201, description: 'Images ajoutées avec succès', type: [CarForSaleImage] })
    async addImages(@Body() createCarForSaleImageDtos: CreateCarForSaleImageDto[]) {
        const uploadedImages = []

        for (const dto of createCarForSaleImageDtos) {
            const fileSchemaValidation = z
                .object({
                    buffer: z.instanceof(Buffer),
                    originalname: z.string(),
                    mimetype: z.string(),
                    size: z.number(),
                })
                .parse(dto)

            const { fileKey } = await this.awsS3Service.uploadFile({ file: fileSchemaValidation })
            const fileUrl = await this.awsS3Service.getFileUrl({ fileKey })

            dto.aws_key = fileKey
            dto.url = fileUrl

            uploadedImages.push(dto)
        }

        return this.carForSaleImageService.addImages(uploadedImages)
    }

    @Get()
    @ApiResponse({ status: 200, description: 'Renvoie toutes les images', type: [CarForSaleImage] })
    async getImages(@Query('ids') ids: string) {
        const idsArray = ids ? ids.split(',').map(Number) : []
        const images = await this.carForSaleImageService.getImages(idsArray)

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

    @Delete(':ids')
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
    async deleteImages(@Param('ids') ids: string) {
        const idsArray = ids.split(',').map((id) => parseInt(id, 10))
        const images = await this.carForSaleImageService.getImages(idsArray)

        for (const image of images) {
            await this.awsS3Service.deleteFile({ fileKey: image.aws_key })
        }

        return this.carForSaleImageService.deleteImages(idsArray)
    }
}
