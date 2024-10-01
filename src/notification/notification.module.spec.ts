import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Notification } from './domaine/notification.entity'
import { NotificationService } from './app/notification.service'
import { NotificationRepository } from './infra/notification.repository'

describe('NotificationModule', () => {
    let notificationService: NotificationService
    let notificationRepository: NotificationRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Notification]),
            ],
            providers: [NotificationService, NotificationRepository],
        }).compile()

        notificationService = module.get<NotificationService>(NotificationService)
        notificationRepository = module.get<NotificationRepository>(NotificationRepository)
    })

    describe('Service', () => {
        it('NotificationService est defini', () => {
            expect(notificationService).toBeDefined()
        })

        it('NotificationService.getNotifications avec aucune notification', async () => {
            const notifications = await notificationService.getNotifications()
            expect(notifications).toEqual([])
        })

        it('NotificationService.getNotifications avec notifications', async () => {
            const notificationCreated = await notificationService.createNotification()
            const notifications = await notificationService.getNotifications()
            expect(notifications).toEqual([notificationCreated])
        })
    })
})
