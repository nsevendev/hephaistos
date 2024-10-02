import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { ChatRoom } from '../domaine/chat-room.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class ChatRoomRepository {
    constructor(
        @InjectRepository(ChatRoom)
        public repository: Repository<ChatRoom>
    ) {}
}
