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
    Put,
} from '@nestjs/common'
import { ServiceService } from './service.service'
import { ApiQuery, ApiResponse, ApiTags } from '@nestjs/swagger'
import { Service } from '../domaine/service.entity'
import { CreateServiceDto } from './create-service.dto'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Service Controller')
@Controller('service')
export class ServiceController {
    constructor(private readonly serviceService: ServiceService) {}

    @Get()
    @ApiQuery({
        name: 'serviceIds',
        type: [Number],
        description: 'Liste des IDs des services à rechercher',
        required: false,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie les services correspondant aux IDs fournis',
        type: [Service],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service correspondant aux IDs fournis`,
    })
    async getServices(@Query('serviceIds') serviceIds?: number[]): Promise<Service[]> {
        const services = await this.serviceService.getServices(serviceIds || [])

        if (serviceIds && serviceIds.length > 0 && services.length === 0) {
            throw new NotFoundException('Aucun service trouvé avec les IDs fournis.')
        }

        return services
    }

    @Post('create')
    @ApiResponse({
        status: 201,
        description: 'Renvoie le service créé',
        type: Service,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Si l'utilisateur spécifié est introuvable`,
    })
    async createService(@Body() createServiceDto: CreateServiceDto): Promise<Service> {
        return this.serviceService.createService(createServiceDto)
    }

    @Put(':id')
    @ApiResponse({
        status: 200,
        description: 'Service mis à jour avec succès',
        type: Service,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Si le nom du service est invalide`,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Si aucun service correspondant à cet ID n'a été trouvé`,
    })
    async updateService(
        @Param('id') serviceId: number,
        @Body('name') updateServiceName: string
    ): Promise<Service> {
        return await this.serviceService.updateService(serviceId, updateServiceName)
    }

    @Delete()
    @ApiQuery({
        name: 'serviceIds',
        type: [Number],
        description: 'Liste des IDs des services à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({ status: 204, description: 'Services supprimés avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service trouvé avec les IDs fournis`,
    })
    async deleteServices(@Query('serviceIds') serviceIds: number[]) {
        return this.serviceService.deleteService(serviceIds)
    }
}
