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
                DatabaseTestModule, // Utilisation de la BDD pour les tests
                TypeOrmModule.forFeature([Notification]),
            ],
            providers: [NotificationService, NotificationRepository],
        }).compile()

        notificationService = module.get<NotificationService>(NotificationService)
        notificationRepository = module.get<NotificationRepository>(NotificationRepository)
    })

    describe('Service', () => {
        it('NotificationService est défini', () => {
            expect(notificationService).toBeDefined()
        })

        it('NotificationService.getNotifications avec aucune notification', async () => {
            const notifications = await notificationService.getNotifications()
            expect(notifications).toEqual([]) // Pas de notifications dans la BDD
        })

        it('NotificationService.createNotification crée une notification avec succès', async () => {
            const notificationCreated = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            const notifications = await notificationService.getNotifications()
            expect(notifications).toContain(notificationCreated) // Vérifie que la notification a été créée
        })

        it('NotificationService.createNotification crée une notification avec les valeurs par défaut', async () => {
            const notificationCreated = await notificationService.createNotification({
                context: 'test',
                message: 'test',
            })
            expect(notificationCreated.readed).toBe(false) // Vérifie que la valeur par défaut de "readed" est `false`
        })

        it('NotificationService.deleteNotification supprime une notification avec succès', async () => {
            const notificationCreated = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            await notificationService.deleteNotification(notificationCreated.id)
            const notifications = await notificationService.getNotifications()
            expect(notifications).toEqual([])
        })

        it("NotificationService.deleteNotification lève une erreur si la notification n'existe pas", async () => {
            const invalidId = 1111
            await expect(notificationService.deleteNotification(invalidId)).rejects.toThrowError(
                `La notification avec l'ID ${invalidId} n'a pas été trouvée.`
            )
        })

        it('NotificationService.getNotifications retourne plusieurs notifications', async () => {
            const notification1 = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            const notification2 = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            const notifications = await notificationService.getNotifications()
            expect(notifications).toEqual([notification1, notification2])
        })

        it('NotificationService.createNotification crée des notifications uniques', async () => {
            const notification1 = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            const notification2 = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            expect(notification1.id).not.toEqual(notification2.id)
        })

        it('NotificationService.updateNotification met à jour une notification', async () => {
            const notification = await notificationService.createNotification({
                context: 'test',
                message: 'test',
                readed: false,
            })
            const updatedNotification = await notificationService.updateNotification(notification.id, {
                readed: true,
            })
            expect(updatedNotification.readed).toBe(true) // Vérifie que la notification a été marquée comme lue
        })
    })
})
