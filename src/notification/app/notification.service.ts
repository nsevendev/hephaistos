import { Injectable, NotFoundException } from '@nestjs/common'
import { NotificationRepository } from '../infra/notification.repository'
import { CreateNotificationDto } from './create-notification.dto'
import { UpdateNotificationDto } from './update-notification.dto'
import { In } from 'typeorm'

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

    getNotifications = async (notificationIds: number[]) => {
        const notifications =
            notificationIds && notificationIds.length > 0
                ? await this.notificationRepository.repository.findBy({ id: In(notificationIds) })
                : await this.notificationRepository.repository.find()

        if (notificationIds && notificationIds.length > 0 && notifications.length === 0) {
            throw new NotFoundException('Aucune notification trouvé avec les IDs fournis.')
        }

        return notifications
    }

    getNotificationsByReadedStatus = async (readed: boolean) => {
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

    async deleteNotifications(notificationIds: number[]) {
        const result = await this.notificationRepository.repository.delete(notificationIds)

        if (result.affected === 0) {
            throw new NotFoundException(`Aucune notification trouvé pour les ID fournis.`)
        }

        const message = {
            message: `${result.affected} notification(s) supprimée`,
            deleteCount: result.affected,
        }

        return message
    }
}
