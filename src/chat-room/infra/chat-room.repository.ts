import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { ChatRoom } from '../domaine/chat-room.entity'

@Injectable()
export class ChatRoomRepository {
    constructor(
        @InjectRepository(ChatRoom)
        public readonly repository: Repository<ChatRoom>
    ) {}
}
