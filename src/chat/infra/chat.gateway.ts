import {
    WebSocketGateway,
    WebSocketServer,
    SubscribeMessage,
    MessageBody,
    ConnectedSocket,
    OnGatewayConnection,
    OnGatewayDisconnect,
} from '@nestjs/websockets'
import { Socket } from 'socket.io'
import { ChatService } from '../app/chat.service'
import { Chat, SenderType } from '../domaine/chat.entity'
import { BadRequestException } from '@nestjs/common'

@WebSocketGateway({
    cors: {
        origin: '*',
    },
})
export class ChatGateway implements OnGatewayConnection, OnGatewayDisconnect {
    @WebSocketServer() server

    constructor(private readonly chatService: ChatService) {}

    handleConnection(socket: Socket) {
        console.log(`Client connecté: ${socket.id}`)
    }

    handleDisconnect(socket: Socket) {
        console.log(`Client déconnecté: ${socket.id}`)
    }

    @SubscribeMessage('joinRoom')
    async handleJoinRoom(@MessageBody() access_code: number, @ConnectedSocket() socket: Socket) {
        socket.join(`room_${access_code}`)
        console.log(`Client ${socket.id} a rejoint la room: ${access_code}`)
        const chats = await this.chatService.getChatsByChatRoom(access_code)
        socket.emit('existingMessages', chats)
    }

    @SubscribeMessage('sendMessage')
    async handleMessage(
        @MessageBody() messageData: { message: string; sender: SenderType; roomId: number }
    ): Promise<void> {
        if (!Object.values(SenderType).includes(messageData.sender)) {
            throw new BadRequestException('L\'expéditeur doit être soit "garage" soit "client".')
        }

        try {
            const chat: Chat = await this.chatService.createChat({
                message: messageData.message,
                room: messageData.roomId,
                sender: messageData.sender,
                readed: false,
            })

            this.server.to(`room_${messageData.roomId}`).emit('message', chat)
        } catch (error) {
            console.error('Erreur lors de la création du message:', error)
            throw new BadRequestException("Erreur lors de l'envoi du message.")
        }
    }
}
