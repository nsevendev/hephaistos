import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Notification } from './domaine/notification.entity'
import { NotificationService } from './app/notification.service'
import { NotificationRepository } from './infra/notification.repository'
import { NotFoundException } from '@nestjs/common'
import { CreateNotificationDto } from './app/create-notification.dto'
import { UpdateNotificationDto } from './app/update-notification.dto'
import { NotificationController } from './app/notification.controller'
import { TypeORMError } from 'typeorm'

describe('NotificationModule', () => {
    let notificationService: NotificationService
    let notificationController: NotificationController
    let notificationRepository: NotificationRepository
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Notification])],
            providers: [NotificationService, NotificationRepository],
            controllers: [NotificationController], // Ajouter le contrôleur ici
        }).compile()

        notificationService = module.get<NotificationService>(NotificationService)
        notificationController = module.get<NotificationController>(NotificationController)
        notificationRepository = module.get<NotificationRepository>(NotificationRepository)
    })

    describe('Service', () => {
        it('NotificationService est défini', () => {
            expect(notificationService).toBeDefined()
        })

        it('NotificationRepository est défini', () => {
            expect(notificationRepository).toBeDefined()
        })

        it('NotificationService.getNotifications avec aucune notification', async () => {
            const notifications = await notificationService.getNotifications([])
            expect(notifications).toEqual([])
        })

        it('NotificationService.createNotification crée une notification avec succès', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'New message received',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)
            const notifications = await notificationService.getNotifications([])

            expect(notifications).toContainEqual(notificationCreated)
        })

        it('NotificationService.getNotifications récupère une notification par ID', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'New message received',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)
            const notifications = await notificationService.getNotifications([notificationCreated.id])

            expect(notifications).toContainEqual(notificationCreated)
        })

        it('NotificationService.getNotifications récupère plusieurs notifications par ID', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'New message received',
                readed: false,
            }

            const notificationData2: CreateNotificationDto = {
                message: 'Another new message received',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)
            const notificationCreated2 = await notificationService.createNotification(notificationData2)
            const notifications = await notificationService.getNotifications([
                notificationCreated.id,
                notificationCreated2.id,
            ])

            expect(notifications).toEqual([notificationCreated, notificationCreated2])
        })

        it('NotificationService.getNotifications retourne une erreur pour des IDs non trouvés', async () => {
            await expect(notificationService.getNotifications([9999])).rejects.toThrow(NotFoundException)
        })

        it('NotificationService.getNotificationsByReadedStatus récupère les notifications filtrées par statut de lecture', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Message read',
                readed: false,
            }
            const notificationCreated = await notificationService.createNotification(notificationData)

            const updateData: UpdateNotificationDto = { readed: true }
            await notificationService.updateNotification(notificationCreated.id, updateData)
            const notifications = await notificationService.getNotificationsByReadedStatus(true)

            expect(notifications.every((n) => n.readed === true)).toBeTruthy()
        })

        it('NotificationService.updateNotification met à jour uniquement le statut de lecture', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Mark as read',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)

            const updateData: UpdateNotificationDto = { readed: true }
            const updatedNotification = await notificationService.updateNotification(
                notificationCreated.id,
                updateData
            )

            expect(updatedNotification.readed).toBe(true)
        })

        it("NotificationService.updateNotification n'autorise pas la mise à jour d'autres propriétés que 'readed'", async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Original message',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)

            const updateData: any = { message: 'New unauthorized message', readed: true }
            const updatedNotification = await notificationService.updateNotification(
                notificationCreated.id,
                updateData
            )

            expect(updatedNotification.readed).toBe(true)
            expect(updatedNotification.message).toBe('Original message')
        })

        it("NotificationService.updateNotification retourne une erreur si la notification n'existe pas", async () => {
            const updateData: UpdateNotificationDto = { readed: true }
            await expect(notificationService.updateNotification(9999, updateData)).rejects.toThrow(
                NotFoundException
            )
        })

        it('NotificationService.deleteNotifications supprime une notification avec succès', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Delete this notification',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)
            await notificationService.deleteNotifications([notificationCreated.id])

            const notifications = await notificationService.getNotifications([])
            expect(notifications).toEqual([])
        })

        it('NotificationService.deleteNotifications supprime des notifications avec succès', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Delete this notification',
                readed: false,
            }
            const notificationData2: CreateNotificationDto = {
                message: 'Delete another notification',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)
            const notificationCreated2 = await notificationService.createNotification(notificationData2)

            await notificationService.deleteNotifications([notificationCreated.id, notificationCreated2.id])

            const notifications = await notificationService.getNotifications([])
            expect(notifications).toEqual([])
        })

        it("NotificationService.deleteNotifications retourne une erreur si aucune notification n'est trouvée", async () => {
            await expect(notificationService.deleteNotifications([9999])).rejects.toThrow(NotFoundException)
        })

        it("NotificationService.deleteNotifications retourne une erreur si le tableau d'IDs est vide", async () => {
            await expect(notificationService.deleteNotifications([])).rejects.toThrow(TypeORMError)
        })
    })

    describe('Controller', () => {
        it('NotificationController est défini', () => {
            expect(notificationController).toBeDefined()
        })

        it('NotificationController.createNotification crée une notification', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Test notification',
                readed: false,
            }

            const result = await notificationController.createNotification(notificationData)

            expect(result.message).toEqual(notificationData.message)
            expect(result.readed).toEqual(notificationData.readed)
        })

        it('NotificationController.getNotifications récupère toutes les notifications', async () => {
            await notificationService.createNotification({ message: 'First notification', readed: false })
            await notificationService.createNotification({ message: 'Second notification', readed: false })

            const notifications = await notificationController.getNotifications([])

            expect(notifications).toHaveLength(2)
        })

        it('NotificationController.getNotifications récupère des notifications par IDs', async () => {
            const notificationData1: CreateNotificationDto = {
                message: 'Test notification 1',
                readed: false,
            }
            const notificationData2: CreateNotificationDto = {
                message: 'Test notification 2',
                readed: false,
            }

            const notification1 = await notificationService.createNotification(notificationData1)
            await notificationService.createNotification(notificationData2)

            const notifications = await notificationController.getNotifications([notification1.id])

            expect(notifications).toContainEqual(notification1)
        })

        it("NotificationController.getNotifications retourne une erreur si aucune notification n'est trouvée", async () => {
            await expect(notificationController.getNotifications([9999])).rejects.toThrow(NotFoundException)
        })

        it('NotificationController.updateNotification met à jour le statut de lecture', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Test update notification',
                readed: false,
            }
            const notificationCreated = await notificationService.createNotification(notificationData)

            const updateData: UpdateNotificationDto = { readed: true }
            const updatedNotification = await notificationController.updateNotification(
                notificationCreated.id,
                updateData
            )

            expect(updatedNotification.readed).toBe(true)
        })

        it("NotificationController.updateNotification retourne une erreur si la notification n'existe pas", async () => {
            const updateData: UpdateNotificationDto = { readed: true }
            await expect(notificationController.updateNotification(9999, updateData)).rejects.toThrow(
                NotFoundException
            )
        })

        it('NotificationController.deleteNotifications supprime des notifications avec succès', async () => {
            const notificationData: CreateNotificationDto = {
                message: 'Delete this notification',
                readed: false,
            }

            const notificationCreated = await notificationService.createNotification(notificationData)
            await notificationController.deleteNotifications([notificationCreated.id])

            const notifications = await notificationService.getNotifications([])
            expect(notifications).toEqual([])
        })

        it("NotificationController.deleteNotifications retourne une erreur si aucune notification n'est trouvée", async () => {
            await expect(notificationController.deleteNotifications([9999])).rejects.toThrow(
                NotFoundException
            )
        })

        it("NotificationController.deleteNotifications retourne une erreur si le tableau d'IDs est vide", async () => {
            await expect(notificationController.deleteNotifications([])).rejects.toThrow(TypeORMError)
        })
    })
})
