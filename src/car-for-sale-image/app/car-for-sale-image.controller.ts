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

@ApiTags('Car For Sale Image Controller')
@Controller('car-for-sale-image')
export class CarForSaleImageController {
    constructor(private readonly carForSaleImageService: CarForSaleImageService) {}

    @Post()
    @ApiResponse({ status: 201, description: 'Images ajoutées avec succès', type: [CarForSaleImage] })
    async addImages(@Body() createCarForSaleImageDto: CreateCarForSaleImageDto[]) {
        return this.carForSaleImageService.addImages(createCarForSaleImageDto)
    }

    @Get()
    @ApiResponse({ status: 200, description: 'Renvoie toutes les images', type: [CarForSaleImage] })
    async getImages(@Query('ids') ids: string) {
        const idsArray = ids ? ids.split(',').map(Number) : []
        return this.carForSaleImageService.getImages(idsArray)
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
        return this.carForSaleImageService.getImagesByCarForSale(carForSaleId)
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
        return this.carForSaleImageService.deleteImages(idsArray)
    }
}
