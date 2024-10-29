import {
    Body,
    ConflictException,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Put,
    Query,
} from '@nestjs/common'
import { MechanicalServiceService } from './mechanical-service.service'
import { CreateMechanicalServiceDto } from './create-mechanical-service.dto'
import { UpdateMechanicalServiceDto } from './update-mechanical-service.dto'
import { MechanicalService } from '../domaine/mechanical-service.entity'
import { ApiBody, ApiParam, ApiQuery, ApiResponse, ApiTags } from '@nestjs/swagger'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('MechanicalService Controller')
@Controller('mechanical-service')
export class MechanicalServiceController {
    constructor(private readonly mechanicalServiceService: MechanicalServiceService) {}

    @Get()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description:
            'Liste des IDs des services mécaniques à récupérer (laisser vide pour obtenir tous les services)',
        required: false,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie tous les services mécaniques ou ceux correspondant aux IDs fournis',
        type: [MechanicalService],
    })
    @ApiResponse({
        status: 404,
        description: 'Aucun service mécanique trouvé avec les IDs fournis.',
        type: NotFoundException,
    })
    async getMechanicalServices(@Query('ids') ids: number[]): Promise<MechanicalService[]> {
        const services = await this.mechanicalServiceService.getMechanicalServices(ids || [])

        return services
    }

    @Get('filter')
    @ApiQuery({
        name: 'search',
        type: String,
        description: 'Critères de filtrage pour les services mécaniques par nom',
        required: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie les services mécaniques filtrés par nom',
        type: [MechanicalService],
    })
    async getMechanicalServicesByFilter(@Query('search') search: string): Promise<MechanicalService[]> {
        return this.mechanicalServiceService.getMechanicalServicesByFilter(search)
    }

    @Post('create')
    @ApiBody({
        type: CreateMechanicalServiceDto,
        description: 'Données nécessaires pour créer un service mécanique',
    })
    @ApiResponse({ status: 200, description: 'Renvoie le service mécanique créé', type: MechanicalService })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Si un service mécanique avec ce nom existe déjà, impossible de créer le service`,
    })
    async createMechanicalService(
        @Body() createMechanicalServiceDto: CreateMechanicalServiceDto
    ): Promise<MechanicalService> {
        return this.mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)
    }

    @Put(':id')
    @ApiParam({
        name: 'id',
        description: 'ID du service mécanique à mettre à jour',
        type: Number,
        required: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie le service mécanique mis à jour',
        type: MechanicalService,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service mécanique trouvé avec cet ID`,
    })
    async updateMechanicalService(
        @Param('id') serviceId: number,
        @Body() updateMechanicalServiceDto: UpdateMechanicalServiceDto
    ): Promise<MechanicalService> {
        const updatedService = await this.mechanicalServiceService.updateMechanicalService(
            serviceId,
            updateMechanicalServiceDto
        )

        return updatedService
    }

    @Delete()
    @ApiQuery({
        name: 'serviceIds',
        type: [Number],
        description: 'Liste des IDs des services mécaniques à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({ status: 204, description: 'Services mécaniques supprimés avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service mécanique trouvé avec les IDs fournis`,
    })
    async deleteMechanicalServices(@Query('serviceIds') serviceIds: number[]): Promise<void> {
        const existingServices = await this.mechanicalServiceService.getMechanicalServices(serviceIds)

        if (existingServices.length === 0) {
            throw new NotFoundException('Aucun service mécanique trouvé avec les IDs fournis')
        }

        await this.mechanicalServiceService.deleteMechanicalServices(serviceIds)
    }
}
