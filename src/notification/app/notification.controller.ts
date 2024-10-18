import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Query,
    Patch,
    BadRequestException,
} from '@nestjs/common'
import { NotificationService } from './notification.service'
import { CreateNotificationDto } from './create-notification.dto'
import { UpdateNotificationDto } from './update-notification.dto'
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { Notification } from '../domaine/notification.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Notification Controller')
@Controller('notification')
export class NotificationController {
    constructor(private readonly notificationService: NotificationService) {}

    @Get()
    @ApiResponse({
        status: 200,
        description:
            'Récupère toutes les notifications ou des notifications spécifiques selon les IDs fournis',
        type: [Notification],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune notification trouvée pour les IDs spécifiés`,
    })
    async getNotifications(@Query('ids') ids: number[]) {
        return this.notificationService.getNotifications(ids)
    }

    @Get('filter')
    @ApiResponse({
        status: 200,
        description: 'Récupère les notifications filtrées par statut de lecture',
        type: [Notification],
    })
    async getNotificationsByFilter(@Query('readed') readed: boolean) {
        return this.notificationService.getByFilter(readed)
    }

    @Post('create')
    @ApiResponse({
        status: 200,
        description: 'Crée une nouvelle notification',
        type: Notification,
    })
    async createNotification(@Body() createNotificationDto: CreateNotificationDto) {
        return this.notificationService.createNotification(createNotificationDto)
    }

    @Patch(':id')
    @ApiResponse({
        status: 200,
        description: 'Met à jour une notification',
        type: Notification,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => La notification spécifiée est introuvable`,
    })
    async updateNotification(@Param('id') id: number, @Body() updateNotificationDto: UpdateNotificationDto) {
        return this.notificationService.updateNotification(id, updateNotificationDto)
    }

    @Delete()
    @ApiResponse({
        status: 200,
        description: 'Supprime les notifications spécifiées par les IDs',
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune notification trouvée pour les IDs spécifiés`,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Le tableau d'IDs ne peut pas être vide`,
    })
    async deleteNotifications(@Body('ids') ids: number[]) {
        return this.notificationService.deleteNotifications(ids)
    }
}
