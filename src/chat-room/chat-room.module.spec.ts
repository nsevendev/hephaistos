import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { ChatRoom } from './domaine/chat-room.entity'
import { ChatRoomService } from './app/chat-room.service'
import { ChatRoomRepository } from './infra/chat-room.repository'
import { ChatRoomController } from './app/chat-room.controller'
import { CreateChatRoomDto } from './app/create-chat-room.dto'
import { UpdateChatRoomDto } from './app/update-chat-room.dto'
import { NotFoundException, ConflictException } from '@nestjs/common'

describe('ChatRoomModule', () => {
    let chatRoomService: ChatRoomService
    let chatRoomController: ChatRoomController
    let chatRoomRepository: ChatRoomRepository
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([ChatRoom])],
            controllers: [ChatRoomController],
            providers: [ChatRoomService, ChatRoomRepository],
        }).compile()

        chatRoomService = module.get<ChatRoomService>(ChatRoomService)
        chatRoomController = module.get<ChatRoomController>(ChatRoomController)
        chatRoomRepository = module.get<ChatRoomRepository>(ChatRoomRepository)
    })

    describe('Service', () => {
        it('ChatRoomService est défini', () => {
            expect(chatRoomService).toBeDefined()
        })

        it('ChatRoomRepository est défini', () => {
            expect(chatRoomRepository).toBeDefined()
        })

        it('ChatRoomService.getChatRooms avec aucune chat room', async () => {
            const chatRooms = await chatRoomService.getChatRooms([])

            expect(chatRooms).toEqual([])
        })

        it('ChatRoomService.createChatRoom crée une chat room avec succès', async () => {
            const chatRoomData: CreateChatRoomDto = {
                email: 'user1@example.com',
                firstname: 'User',
                lastname: 'One',
            }
            const chatRoomCreated = await chatRoomService.createChatRoom(chatRoomData)
            const chatRooms = await chatRoomService.getChatRooms([])

            expect(chatRoomCreated.email).toEqual(chatRoomData.email)
            expect(chatRooms).toContainEqual(chatRoomCreated)
        })

        it('ChatRoomService.createChatRoom retourne une erreur si une chat room avec le même email existe', async () => {
            const chatRoomData: CreateChatRoomDto = {
                email: 'user5@example.com',
                firstname: 'User',
                lastname: 'Five',
            }

            await chatRoomService.createChatRoom(chatRoomData)

            await expect(chatRoomService.createChatRoom(chatRoomData)).rejects.toThrow(ConflictException)
        })

        it('ChatRoomService.getChatRoom récupère une chat room par ID', async () => {
            const chatRoomData: CreateChatRoomDto = {
                email: 'user2@example.com',
                firstname: 'User',
                lastname: 'Two',
            }

            const chatRoomCreated = await chatRoomService.createChatRoom(chatRoomData)
            const chatRoomRetrieved = await chatRoomService.getChatRooms([chatRoomCreated.id])

            expect(chatRoomRetrieved).toHaveLength(1)
            expect(chatRoomRetrieved[0]).toEqual(chatRoomCreated)
        })

        it('ChatRoomService.updateChatRoom met à jour les informations de la chat room', async () => {
            const chatRoomData: CreateChatRoomDto = {
                email: 'user3@example.com',
                firstname: 'User',
                lastname: 'Three',
            }

            const chatRoomCreated = await chatRoomService.createChatRoom(chatRoomData)
            const updatedData: UpdateChatRoomDto = { firstname: 'UpdatedUser' }
            const updatedChatRoom = await chatRoomService.updateChatRoom(chatRoomCreated.id, updatedData)

            expect(updatedChatRoom.firstname).toBe('UpdatedUser')
        })

        it('ChatRoomService.deleteChatRooms supprime une chat room avec succès', async () => {
            const chatRoomData: CreateChatRoomDto = {
                email: 'user4@example.com',
                firstname: 'User',
                lastname: 'Four',
            }

            const chatRoomCreated = await chatRoomService.createChatRoom(chatRoomData)
            await chatRoomService.deleteChatRooms([chatRoomCreated.id])

            const chatRooms = await chatRoomService.getChatRooms([])

            expect(chatRooms).toEqual([])
        })

        it("ChatRoomService.deleteChatRooms retourne une erreur si la chat room n'existe pas", async () => {
            const invalidChatRoomId = 999

            await expect(chatRoomService.deleteChatRooms([invalidChatRoomId])).rejects.toThrow(
                NotFoundException
            )
        })
    })

    describe('Controller', () => {
        it('ChatRoomController est défini', () => {
            expect(chatRoomController).toBeDefined()
        })

        it('ChatRoomController.createChatRoom crée une chat room', async () => {
            const chatRoomData: CreateChatRoomDto = {
                email: 'user5@example.com',
                firstname: 'User',
                lastname: 'Five',
            }

            const result = await chatRoomController.createChatRoom(chatRoomData)
            expect(result.email).toEqual(chatRoomData.email)
        })

        it('ChatRoomController.getChatRooms récupère toutes les chat rooms', async () => {
            await chatRoomService.createChatRoom({
                email: 'user6@example.com',
                firstname: 'User',
                lastname: 'Six',
            })

            const result = await chatRoomController.getChatRooms([])
            expect(result).toHaveLength(1)
        })

        it('ChatRoomController.getChatRooms - chat room existante', async () => {
            const chatRoomCreated = await chatRoomService.createChatRoom({
                email: 'user7@example.com',
                firstname: 'User',
                lastname: 'Seven',
            })

            const result = await chatRoomController.getChatRooms([chatRoomCreated.id])
            expect(result).toBeDefined()
            expect(result[0].id).toEqual(chatRoomCreated.id)
        })

        it('ChatRoomController.getChatRooms - chat room inexistante', async () => {
            await expect(chatRoomController.getChatRooms([999])).rejects.toThrow(NotFoundException)
        })

        it('ChatRoomController.updateChatRoom met à jour une chat room', async () => {
            const chatRoomCreated = await chatRoomService.createChatRoom({
                email: 'user8@example.com',
                firstname: 'User',
                lastname: 'Eight',
            })

            const updatedData: UpdateChatRoomDto = { firstname: 'UpdatedUser' }
            const result = await chatRoomController.updateChatRoom(chatRoomCreated.id, updatedData)
            expect(result.firstname).toEqual('UpdatedUser')
        })

        it('ChatRoomController.deleteChatRooms supprime une chat room', async () => {
            const chatRoomCreated = await chatRoomService.createChatRoom({
                email: 'user9@example.com',
                firstname: 'User',
                lastname: 'Nine',
            })

            await chatRoomController.deleteChatRooms([chatRoomCreated.id])
            await expect(chatRoomService.getChatRooms([chatRoomCreated.id])).rejects.toThrow(
                NotFoundException
            )
        })

        it('ChatRoomController.deleteChatRooms - chat room inexistante', async () => {
            await expect(chatRoomController.deleteChatRooms([999])).rejects.toThrow(NotFoundException)
        })
    })
})
