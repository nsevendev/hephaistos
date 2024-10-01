import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { NotificationRepository } from '../infra/notification.repository'

@Injectable()
export class NotificationService extends BaseService {
    constructor(private readonly notificationRepository: NotificationRepository) {
        super('NotificationService')
    }

    getNotifications = async () => {
        return await this.notificationRepository.repository.find()
    }

    createNotification = () => {
        return this.notificationRepository.repository.create()
    }
}
