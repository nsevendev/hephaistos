import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Notification } from '../domaine/notification.entity'

@Injectable()
export class NotificationRepository {
    constructor(
        @InjectRepository(Notification)
        public readonly repository: Repository<Notification>
    ) {}
}
