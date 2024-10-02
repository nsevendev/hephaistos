import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { ChatRepository } from '../infra/chat.repository'

@Injectable()
export class ChatService extends BaseService {
    constructor(private readonly chatRepository: ChatRepository) {
        super('ChatService')
    }

    getChats = async () => {
        return await this.chatRepository.repository.find()
    }

    createChat = () => {
        return this.chatRepository.repository.create()
    }
}
