import {
    Body,
    Controller,
    Delete,
    Get,
    Param,
    Post,
    Put,
    BadRequestException,
    NotFoundException,
} from '@nestjs/common'
import { CarForSaleService } from './car-for-sale.service'
import { CreateCarForSaleDto } from './create-car-for-sale.dto'
import { UpdateCarForSaleDto } from './update-car-for-sale.dto'
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { CarForSale } from '../domaine/car-for-sale.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Car For Sale Controller')
@Controller('car-for-sale')
export class CarForSaleController {
    constructor(private readonly carForSaleService: CarForSaleService) {}

    @Post('create')
    @ApiResponse({ status: 200, description: 'Renvoie la voiture créée', type: CarForSale })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Erreur lors de la création de la voiture`,
    })
    async createCarForSale(@Body() createCarForSaleDto: CreateCarForSaleDto) {
        return this.carForSaleService.createCarForSale(createCarForSaleDto)
    }

    @Get(':id')
    @ApiResponse({ status: 200, description: "Renvoie la voiture correspondant à l'ID", type: CarForSale })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune voiture trouvée avec cet ID`,
    })
    async getCarForSale(@Param('id') carId: number[]) {
        const cars = await this.carForSaleService.getCarForSales(carId)
        if (cars.length === 0) {
            throw new NotFoundException(`Aucune voiture trouvée avec l'ID ${carId}`)
        }
        return cars[0]
    }

    @Put(':id')
    @ApiResponse({ status: 200, description: 'Voiture mise à jour avec succès', type: CarForSale })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune voiture trouvée avec cet ID`,
    })
    async updateCarForSale(@Param('id') carId: number, @Body() updateCarForSaleDto: UpdateCarForSaleDto) {
        return this.carForSaleService.updateCarForSale(carId, updateCarForSaleDto)
    }

    @Delete(':id')
    @ApiResponse({ status: 204, description: 'Voiture supprimée avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune voiture trouvée avec cet ID`,
    })
    async deleteCarForSale(@Param('id') carId: number) {
        await this.carForSaleService.deleteCarForSale([carId])
        return
    }

    @Delete()
    @ApiResponse({ status: 204, description: 'Voitures supprimées avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune voiture trouvée avec les IDs fournis`,
    })
    async deleteManyCarsForSale(@Body() carIds: number[]) {
        await this.carForSaleService.deleteCarForSale(carIds)
        return
    }
}
