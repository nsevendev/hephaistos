import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { ChatRoom } from '../domaine/chat-room.entity'
import { CreateChatRoomDto } from '../app/create-chat-room.dto'

@Injectable()
export class ChatRoomRepository {
    constructor(
        @InjectRepository(ChatRoom)
        public readonly repository: Repository<ChatRoom>
    ) {}

    async createUniqueAccessCode(): Promise<string> {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
        let accessCode: string

        do {
            accessCode = ''
            for (let i = 0; i < 8; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length)
                accessCode += characters.charAt(randomIndex)
            }
        } while (await this.checkAccessCodeExists(accessCode))

        return accessCode
    }

    async checkAccessCodeExists(accessCode: string): Promise<boolean> {
        const existingRoom = await this.repository.findOne({ where: { access_code: accessCode } })
        return existingRoom !== undefined
    }

    async createChatRoom(createChatRoomDto: CreateChatRoomDto) {
        const chatRoom = this.repository.create(createChatRoomDto)

        chatRoom.access_code = await this.createUniqueAccessCode()

        return await this.repository.save(chatRoom)
    }
}
