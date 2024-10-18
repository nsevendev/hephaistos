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
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('MechanicalService Controller')
@Controller('mechanical-service')
export class MechanicalServiceController {
    constructor(private readonly mechanicalServiceService: MechanicalServiceService) {}

    @Get()
    @ApiResponse({
        status: 200,
        description: 'Renvoie tous les services mécaniques ou ceux correspondant aux IDs fournis',
        type: [MechanicalService],
    })
    async getMechanicalService(@Query('ids') ids: number[]) {
        const services = await this.mechanicalServiceService.getMechanicalService(ids || [])

        if (ids && ids.length > 0 && services.length === 0) {
            throw new NotFoundException('Aucun service mécanique trouvé avec les IDs fournis.')
        }

        return services
    }

    @Get('filter')
    @ApiResponse({
        status: 200,
        description: 'Renvoie les services mécaniques filtrés par nom',
        type: [MechanicalService],
    })
    async getMechanicalServiceByFilter(@Query('search') search: string) {
        return this.mechanicalServiceService.getMechanicalServiceByFilter(search)
    }

    @Post('create')
    @ApiResponse({ status: 200, description: 'Renvoie le service mécanique créé', type: MechanicalService })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Si un service mécanique avec ce nom existe déjà, impossible de créer le service`,
    })
    async createMechanicalService(@Body() createMechanicalServiceDto: CreateMechanicalServiceDto) {
        return this.mechanicalServiceService.createMechanicalService(createMechanicalServiceDto)
    }

    @Put(':id')
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
    ) {
        return this.mechanicalServiceService.updateMechanicalService(serviceId, updateMechanicalServiceDto)
    }

    @Delete(':id')
    @ApiResponse({ status: 204, description: 'Service mécanique supprimé avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun service mécanique trouvé avec cet ID`,
    })
    async deleteMechanicalService(@Param('id') serviceId: number) {
        await this.mechanicalServiceService.deleteMechanicalService(serviceId)
        return
    }
}
