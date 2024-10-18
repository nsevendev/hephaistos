import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Chat } from '../domaine/chat.entity'
import { CreateChatDto } from '../app/create-chat.dto'

@Injectable()
export class ChatRepository {
    constructor(
        @InjectRepository(Chat)
        public readonly repository: Repository<Chat>
    ) {}

    async createChat(createChatDto: CreateChatDto) {
        const chatRoom = this.repository.create(createChatDto)

        return await this.repository.save(chatRoom)
    }
}
