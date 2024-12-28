import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Put,
    BadRequestException,
    Query,
} from '@nestjs/common'
import { AppointmentService } from './appointment.service'
import { CreateAppointmentDto } from './create-appointment.dto'
import { UpdateAppointmentDto } from './update-appointment.dto'
import { ApiResponse, ApiTags, ApiBody, ApiQuery } from '@nestjs/swagger'
import { Appointment } from '../domaine/appointment.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Appointment Controller')
@Controller('appointments')
export class AppointmentController {
    constructor(private readonly appointmentService: AppointmentService) {}

    @Post()
    @ApiBody({
        type: CreateAppointmentDto,
        description: 'Données nécessaires pour créer un nouveau rendez-vous',
    })
    @ApiResponse({ status: 201, description: 'Appointment créé avec succès', type: Appointment })
    async createAppointment(@Body() createAppointmentDto: CreateAppointmentDto) {
        return this.appointmentService.addAppointment(createAppointmentDto)
    }

    @Get()
    @ApiQuery({
        name: 'ids',
        required: false,
        description: 'Liste des IDs des appointments à récupérer, séparés par des virgules',
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie tous les appointments ou ceux spécifiés par les IDs',
        type: [Appointment],
    })
    async getAppointment(@Query('ids') ids: string) {
        const idsArray = ids ? ids.split(',').map(Number) : []
        return this.appointmentService.getAppointment(idsArray)
    }

    @Get('date')
    @ApiQuery({
        name: 'startDate',
        required: true,
        description: 'Date de début pour le filtrage des appointments',
    })
    @ApiQuery({
        name: 'endDate',
        required: true,
        description: 'Date de fin pour le filtrage des appointments',
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie tous les appointments dans une plage de dates spécifique',
        type: [Appointment],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun appointment trouvé dans la plage de dates spécifiée`,
    })
    async getAppointmentsByDate(@Query('startDate') startDate: string, @Query('endDate') endDate: string) {
        const start = new Date(startDate)
        const end = new Date(endDate)
        return this.appointmentService.getAppointmentsByDate(start, end)
    }

    @Put(':ids')
    @ApiBody({ type: UpdateAppointmentDto, description: 'Données pour mettre à jour les appointments' })
    @ApiResponse({ status: 200, description: 'Appointments mis à jour avec succès', type: [Appointment] })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun appointment trouvé avec les IDs spécifiés`,
    })
    async updateAppointments(@Param('ids') ids: string, @Body() updateAppointmentDto: UpdateAppointmentDto) {
        const idsArray = ids.split(',').map(Number)
        return this.appointmentService.updateAppointments(idsArray, updateAppointmentDto)
    }

    @Delete(':ids')
    @ApiResponse({ status: 204, description: 'Appointments supprimés avec succès' })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni ou tableau d'IDs vide`,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun appointment trouvé avec les IDs spécifiés`,
    })
    async deleteAppointments(@Param('ids') ids: string) {
        const idsArray = ids.split(',').map((id) => parseInt(id, 10))
        return this.appointmentService.deleteAppointments(idsArray)
    }
}
