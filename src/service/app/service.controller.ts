import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    BadRequestException,
} from '@nestjs/common'
import { ServiceService } from './service.service'
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { Service } from '../domaine/service.entity'
import { CreateServiceDto } from './create-service.dto'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Service Controller')
@Controller('service')
export class ServiceController {
    constructor(private readonly serviceService: ServiceService) {}

    @Get(':ids')
    @ApiResponse({ status: 200, description: "Renvoie les services correspondant aux ID's", type: Service })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service correspondants aux ID's`,
    })
    async getServices(@Param('ids') serviceIds: number[]) {
        const services = await this.serviceService.getServices(serviceIds)
        return services.length ? services : null
    }

    @Post('create')
    @ApiResponse({ status: 201, description: 'Renvoie le service créé', type: Service })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Si l'utilisateur spécifié est introuvable`,
    })
    async createService(@Body() createServiceDto: CreateServiceDto) {
        return this.serviceService.createService(createServiceDto)
    }

    @Delete(':id')
    @ApiResponse({ status: 204, description: 'Service supprimé avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service trouvé avec cet ID`,
    })
    async deleteService(@Param('id') serviceId: number) {
        await this.serviceService.deleteService([serviceId])
    }
}
