import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { NotificationRepository } from '../infra/notification.repository'
import { CreateNotificationDto } from './create-notification.dto'
import { UpdateNotificationDto } from './update-notification.dto'

@Injectable()
export class NotificationService {
    constructor(private readonly notificationRepository: NotificationRepository) {}

    createNotification = async (createNotificationDto: CreateNotificationDto) => {
        const { message, readed } = createNotificationDto

        const newNotification = this.notificationRepository.repository.create({
            message,
            readed,
        })

        return await this.notificationRepository.repository.save(newNotification)
    }

    getNotifications = async (ids: number[]) => {
        if (ids.length === 0) {
            return await this.notificationRepository.repository.find()
        }

        const notifications = await this.notificationRepository.repository.findByIds(ids)

        if (notifications.length === 0) {
            throw new NotFoundException('Aucune notification trouvée pour les IDs spécifiés.')
        }

        return notifications
    }

    getByFilter = async (readed: boolean) => {
        return await this.notificationRepository.repository.find({ where: { readed } })
    }

    updateNotification = async (id: number, updateNotificationDto: UpdateNotificationDto) => {
        const existingNotification = await this.notificationRepository.repository.findOne({ where: { id } })

        if (!existingNotification) {
            throw new NotFoundException('La notification spécifiée est introuvable.')
        }

        const updatedNotification = {
            ...existingNotification,
            readed:
                updateNotificationDto.readed !== undefined
                    ? updateNotificationDto.readed
                    : existingNotification.readed,
        }

        return this.notificationRepository.repository.save(updatedNotification)
    }

    deleteNotifications = async (ids: number[]) => {
        if (ids.length === 0) {
            throw new BadRequestException("Le tableau d'IDs ne peut pas être vide.")
        }

        const notifications = await this.notificationRepository.repository.findByIds(ids)

        if (notifications.length === 0) {
            throw new NotFoundException('Aucune notification trouvée pour les IDs spécifiés.')
        }

        await this.notificationRepository.repository.remove(notifications)
    }
}
