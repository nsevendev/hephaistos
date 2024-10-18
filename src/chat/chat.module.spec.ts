import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { ChatService } from './app/chat.service'
import { ChatRepository } from './infra/chat.repository'
import { ChatRoomService } from '../chat-room/app/chat-room.service'
import { CreateChatDto } from './app/create-chat.dto'
import { UpdateChatDto } from './app/update-chat.dto'
import { NotFoundException } from '@nestjs/common'
import { Chat, SenderType } from './domaine/chat.entity'
import { ChatRoomModule } from '../chat-room/chat-room.module'
import { ChatController } from './app/chat.controller'

describe('ChatModule', () => {
    let chatService: ChatService
    let chatRepository: ChatRepository
    let chatRoomService: ChatRoomService
    let chatController: ChatController
    let module: TestingModule

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([Chat]), ChatRoomModule],
            providers: [ChatService, ChatRepository],
            controllers: [ChatController],
        }).compile()

        chatService = module.get<ChatService>(ChatService)
        chatRepository = module.get<ChatRepository>(ChatRepository)
        chatRoomService = module.get<ChatRoomService>(ChatRoomService)
        chatController = module.get<ChatController>(ChatController)
    })

    describe('Service', () => {
        it('ChatService est défini', () => {
            expect(chatService).toBeDefined()
        })

        it('ChatRepository est défini', () => {
            expect(chatRepository).toBeDefined()
        })

        it('ChatRoomService est défini', () => {
            expect(chatRoomService).toBeDefined()
        })

        it('ChatService.createChat crée un chat avec succès', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client@example.com',
                firstname: 'John',
                lastname: 'Doe',
            })

            const createChatData: CreateChatDto = {
                room: chatRoom.id,
                message: "Bonjour, j'ai des questions sur la reprogrammation automobile",
                sender: SenderType.CLIENT,
                readed: false,
            }

            const chatCreated = await chatService.createChat(createChatData)

            expect(chatCreated.message).toEqual(createChatData.message)
            expect(chatCreated.sender).toEqual(createChatData.sender)
            expect(chatCreated.readed).toEqual(createChatData.readed)
            expect(chatCreated.room.id).toEqual(chatRoom.id)
        })

        it("ChatService.createChat retourne une erreur si la chat room n'existe pas", async () => {
            const createChatData: CreateChatDto = {
                room: 999,
                message: "Bonjour, j'ai des questions sur la reprogrammation automobile",
                sender: SenderType.CLIENT,
                readed: false,
            }

            await expect(chatService.createChat(createChatData)).rejects.toThrow(NotFoundException)
        })

        it('ChatService.getChats retourne un tableau de chats', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'test2@example.com',
                firstname: 'Jane',
                lastname: 'Doe',
            })

            await chatService.createChat({
                room: chatRoom.id,
                message: 'Premier message',
                sender: SenderType.CLIENT,
                readed: false,
            })

            await chatService.createChat({
                room: chatRoom.id,
                message: 'Deuxième message',
                sender: SenderType.GARAGE,
                readed: false,
            })

            const chats = await chatService.getChats([1, 2])
            expect(chats).toHaveLength(2)
        })

        it('ChatService.updateChat met à jour un chat avec succès', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client@example.com',
                firstname: 'John',
                lastname: 'Doe',
            })

            const chatCreated = await chatService.createChat({
                room: chatRoom.id,
                message: 'Chat à mettre à jour',
                sender: SenderType.CLIENT,
                readed: false,
            })

            const updateData: UpdateChatDto = { message: 'Message mis à jour' }
            const updatedChat = await chatService.updateChat(chatCreated.id, updateData)

            expect(updatedChat.message).toEqual('Message mis à jour')
            expect(updatedChat.sender).toEqual(chatCreated.sender)
            expect(updatedChat.readed).toEqual(chatCreated.readed)
            expect(updatedChat.room).toBeDefined()
            expect(updatedChat.room.id).toEqual(chatRoom.id)
        })
    })

    describe('Controller', () => {
        it('ChatController est défini', () => {
            expect(chatController).toBeDefined()
        })

        it('ChatController.createChat crée un chat', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client@example.com',
                firstname: 'John',
                lastname: 'Doe',
            })

            const createChatData: CreateChatDto = {
                room: chatRoom.id,
                message: "Bonjour, j'ai des questions sur la reprogrammation automobile",
                sender: SenderType.CLIENT,
                readed: false,
            }

            const result = await chatController.createChat(createChatData)
            expect(result.message).toEqual(createChatData.message)
            expect(result.sender).toEqual(createChatData.sender)
            expect(result.readed).toEqual(createChatData.readed)
            expect(result.room.id).toEqual(chatRoom.id)
        })

        it("ChatController.createChat retourne une erreur si la chat room n'existe pas", async () => {
            const createChatData: CreateChatDto = {
                room: 999,
                message: "Bonjour, j'ai des questions sur la reprogrammation automobile",
                sender: SenderType.CLIENT,
                readed: false,
            }

            await expect(chatController.createChat(createChatData)).rejects.toThrow(NotFoundException)
        })

        it('ChatController.getAllChats récupère tous les chats', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client2@example.com',
                firstname: 'Jane',
                lastname: 'Doe',
            })

            await chatService.createChat({
                room: chatRoom.id,
                message: 'Premier message',
                sender: SenderType.CLIENT,
                readed: false,
            })

            const result = await chatController.getAllChats('')
            expect(result).toHaveLength(1)
        })

        it("ChatController.getChatsByChatRoom récupère les chats d'une chat room", async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client3@example.com',
                firstname: 'Jim',
                lastname: 'Beam',
            })

            await chatService.createChat({
                room: chatRoom.id,
                message: 'Un message',
                sender: SenderType.CLIENT,
                readed: false,
            })

            const result = await chatController.getChatsByChatRoom(chatRoom.id)

            expect(result).toHaveLength(1)
            expect(result[0].message).toEqual('Un message')
        })

        it('ChatController.updateChat met à jour un chat', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client4@example.com',
                firstname: 'Jack',
                lastname: 'Daniels',
            })

            const chatCreated = await chatService.createChat({
                room: chatRoom.id,
                message: 'Chat à mettre à jour',
                sender: SenderType.CLIENT,
                readed: false,
            })

            const updateData: UpdateChatDto = { message: 'Message mis à jour' }
            const result = await chatController.updateChat(chatCreated.id, updateData)

            expect(result.message).toEqual('Message mis à jour')
        })

        it('ChatController.deleteChats supprime un chat', async () => {
            const chatRoom = await chatRoomService.createChatRoom({
                email: 'client5@example.com',
                firstname: 'Johnny',
                lastname: 'Walker',
            })

            const chatCreated = await chatService.createChat({
                room: chatRoom.id,
                message: 'Un message à supprimer',
                sender: SenderType.CLIENT,
                readed: false,
            })

            await chatController.deleteChats(`${chatCreated.id}`)
            await expect(chatService.getChats([chatCreated.id])).rejects.toThrow(NotFoundException)
        })

        it("ChatController.deleteChats retourne une erreur si le chat n'existe pas", async () => {
            await expect(chatController.deleteChats('999')).rejects.toThrow(NotFoundException)
        })
    })
})
