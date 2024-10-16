import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { ChatRoom } from '../domaine/chat-room.entity'
import { CreateChatRoomDto } from '../app/create-chat-room.dto'
import { v4 as uuidv4 } from 'uuid'

@Injectable()
export class ChatRoomRepository {
    constructor(
        @InjectRepository(ChatRoom)
        public readonly repository: Repository<ChatRoom>
    ) {}

    async createChatRoom(createChatRoomDto: CreateChatRoomDto) {
        const chatRoom = this.repository.create(createChatRoomDto)

        chatRoom.access_code = uuidv4()

        return await this.repository.save(chatRoom)
    }
}
