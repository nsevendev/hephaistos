import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Query,
    BadRequestException,
    Put,
} from '@nestjs/common'
import { NotificationService } from './notification.service'
import { CreateNotificationDto } from './create-notification.dto'
import { UpdateNotificationDto } from './update-notification.dto'
import { ApiBody, ApiQuery, ApiResponse, ApiTags } from '@nestjs/swagger'
import { Notification } from '../domaine/notification.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Notification Controller')
@Controller('notification')
export class NotificationController {
    constructor(private readonly notificationService: NotificationService) {}

    @Post('create')
    @ApiBody({ type: CreateNotificationDto, description: 'Données pour créer une notification' })
    @ApiResponse({
        status: 200,
        description: 'Renvoie la notification créée',
        type: Notification,
    })
    async createNotification(@Body() createNotificationDto: CreateNotificationDto) {
        return this.notificationService.createNotification(createNotificationDto)
    }

    @Get()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs de notifications à rechercher',
        required: true,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie les notifications correspondant aux IDs fournis',
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
    @ApiQuery({
        name: 'readed',
        type: Boolean,
        description: 'Statut de lecture des notifications à filtrer',
        required: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie les notifications filtrées par statut de lecture',
        type: [Notification],
    })
    async getNotificationsByReadedStatus(@Query('readed') readed: boolean) {
        return this.notificationService.getNotificationsByReadedStatus(readed)
    }

    @Put(':id')
    @ApiBody({ type: UpdateNotificationDto, description: 'Données pour mettre à jour une notification' })
    @ApiResponse({
        status: 200,
        description: 'Notification mise à jour avec succès',
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
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs de notifications à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({ status: 204, description: 'Notifications supprimées avec succès' })
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
    async deleteNotifications(@Query('ids') ids: number[]) {
        return this.notificationService.deleteNotifications(ids)
    }
}
