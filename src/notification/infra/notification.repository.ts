import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Notification } from '../domaine/notification.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class NotificationRepository {
    constructor(
        @InjectRepository(Notification)
        public repository: Repository<Notification>
    ) {}
}
