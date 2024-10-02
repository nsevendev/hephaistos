import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Chat } from './domaine/chat.entity'
import { ChatService } from './app/chat.service'
import { ChatRepository } from './infra/chat.repository'

describe('ChatModule', () => {
    let chatService: ChatService
    let chatRepository: ChatRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Chat]),
            ],
            providers: [ChatService, ChatRepository],
        }).compile()

        chatService = module.get<ChatService>(ChatService)
        chatRepository = module.get<ChatRepository>(ChatRepository)
    })

    describe('Service', () => {
        it('ChatService est defini', () => {
            expect(chatService).toBeDefined()
        })

        it('ChatService.getChats avec aucun chat', async () => {
            const chats = await chatService.getChats()
            expect(chats).toEqual([])
        })

        it('ChatService.getChats avec chat', async () => {
            const chatCreated = await chatService.createChat()
            const chats = await chatService.getChats()
            expect(chats).toEqual([chatCreated])
        })
    })
})
