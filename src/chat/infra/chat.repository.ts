import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Chat } from '../domaine/chat.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class ChatRepository {
    constructor(
        @InjectRepository(Chat)
        public repository: Repository<Chat>
    ) {}
}
