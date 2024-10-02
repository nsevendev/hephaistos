import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { ChatRoom } from './domaine/chat-room.entity'
import { ChatRoomService } from './app/chat-room.service'
import { ChatRoomRepository } from './infra/chat-room.repository'

describe('ChatRoomModule', () => {
    let chatRoomService: ChatRoomService
    let chatRoomRepository: ChatRoomRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([ChatRoom]),
            ],
            providers: [ChatRoomService, ChatRoomRepository],
        }).compile()

        chatRoomService = module.get<ChatRoomService>(ChatRoomService)
        chatRoomRepository = module.get<ChatRoomRepository>(ChatRoomRepository)
    })

    describe('Service', () => {
        it('ChatRoomService est defini', () => {
            expect(chatRoomService).toBeDefined()
        })

        it('ChatRoomService.getChatRooms avec aucune chat room', async () => {
            const chatRooms = await chatRoomService.getChatRooms()
            expect(chatRooms).toEqual([])
        })

        it('ChatRoomService.getChatRooms avec des chat room', async () => {
            const chatRoomCreated = await chatRoomService.createChatRoom()
            const chatRooms = await chatRoomService.getChatRooms()
            expect(chatRooms).toEqual([chatRoomCreated])
        })
    })
})
