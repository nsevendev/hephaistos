import { Module } from '@nestjs/common'
import { NotificationService } from './app/notification.service'
import { NotificationRepository } from './infra/notification.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Notification } from './domaine/notification.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Notification])],
    providers: [NotificationService, NotificationRepository],
    exports: [NotificationService, NotificationRepository],
})
export class NotificationModule {}
