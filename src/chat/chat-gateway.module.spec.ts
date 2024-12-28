import { Test, TestingModule } from '@nestjs/testing'
import { ChatGateway } from './infra/chat.gateway'
import { ChatService } from './app/chat.service'
import { Socket } from 'socket.io'
import { SenderType } from './domaine/chat.entity'
import { BadRequestException } from '@nestjs/common'

describe('ChatGateway', () => {
    let gateway: ChatGateway
    let chatService: ChatService
    let mockSocket: Socket

    const mockServer = {
        to: jest.fn().mockReturnThis(),
        emit: jest.fn(),
    }

    beforeEach(async () => {
        // Mock de console.log et console.error
        jest.spyOn(console, 'log').mockImplementation(() => {})
        jest.spyOn(console, 'error').mockImplementation(() => {})

        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ChatGateway,
                {
                    provide: ChatService,
                    useValue: {
                        getChatsByChatRoom: jest.fn(),
                        createChat: jest.fn(),
                    },
                },
            ],
        }).compile()

        gateway = module.get<ChatGateway>(ChatGateway)
        chatService = module.get<ChatService>(ChatService)

        gateway.server = mockServer

        mockSocket = {
            id: 'mockSocketId',
            join: jest.fn(),
            emit: jest.fn(),
            to: jest.fn().mockReturnThis(),
        } as unknown as Socket
    })

    afterEach(() => {
        // Restaurer le comportement original de console.log et console.error après chaque test
        jest.restoreAllMocks()
    })

    it('doit être défini', () => {
        expect(gateway).toBeDefined()
    })

    it('handleConnection doit se connecter avec succès', () => {
        gateway.handleConnection(mockSocket)
        expect(mockSocket.id).toBe('mockSocketId')
    })

    it('handleDisconnect doit se déconnecter avec succès', () => {
        gateway.handleDisconnect(mockSocket)
    })

    it('handleJoinRoom doit rejoindre la room et récupérer les messages existants', async () => {
        const accessCode = 123
        const existingMessages = [{ message: 'Bonjour', sender: SenderType.CLIENT, room: accessCode }]
        chatService.getChatsByChatRoom = jest.fn().mockResolvedValue(existingMessages)

        await gateway.handleJoinRoom(accessCode, mockSocket)
        expect(mockSocket.join).toHaveBeenCalledWith(`room_${accessCode}`)
        expect(mockSocket.emit).toHaveBeenCalledWith('existingMessages', existingMessages)
    })

    it('handleMessage doit poster un message avec succès', async () => {
        const messageData = { message: 'Salut', sender: SenderType.CLIENT, roomId: 1 }
        const createdChat = { ...messageData, id: 1, readed: false }
        chatService.createChat = jest.fn().mockResolvedValue(createdChat)

        await gateway.handleMessage(messageData)

        expect(chatService.createChat).toHaveBeenCalledWith({
            message: messageData.message,
            room: messageData.roomId,
            sender: messageData.sender,
            readed: false,
        })

        expect(gateway.server.to(`room_${messageData.roomId}`).emit).toHaveBeenCalledWith(
            'message',
            createdChat
        )
    })

    it("handleMessage doit renvoyer une erreur si le sender n'est pas valide", async () => {
        const messageData: { message: string; sender: any; roomId: number } = {
            message: 'Salut',
            sender: 'invalidSender',
            roomId: 1,
        }

        await expect(gateway.handleMessage(messageData)).rejects.toThrow(BadRequestException)
    })

    it("handleMessage doit renvoyer une erreur lors de l'envoi d'un message en cas d'erreur du service", async () => {
        const messageData = { message: 'Salut', sender: SenderType.CLIENT, roomId: 1 }
        chatService.createChat = jest.fn().mockRejectedValue(new Error('Erreur de service'))

        await expect(gateway.handleMessage(messageData)).rejects.toThrow(BadRequestException)
    })
})
