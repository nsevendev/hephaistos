import { Module } from '@nestjs/common'
import { TypeOrmModule } from '@nestjs/typeorm'
import { NotificationService } from './app/notification.service'
import { NotificationRepository } from './infra/notification.repository'
import { Notification } from './domaine/notification.entity'
import { NotificationController } from './app/notification.controller'

@Module({
    imports: [TypeOrmModule.forFeature([Notification])],
    providers: [NotificationService, NotificationRepository],
    exports: [NotificationService, NotificationRepository],
    controllers: [NotificationController],
})
export class NotificationModule {}
