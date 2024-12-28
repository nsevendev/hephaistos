import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Chat } from '../domaine/chat.entity'

@Injectable()
export class ChatRepository {
    constructor(
        @InjectRepository(Chat)
        public readonly repository: Repository<Chat>
    ) {}

    async createChat(chat: Chat) {
        return await this.repository.save(chat)
    }
}
