import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { ChatRoomRepository } from '../infra/chat-room.repository'

@Injectable()
export class ChatRoomService extends BaseService {
    constructor(private readonly chatRoomRepository: ChatRoomRepository) {
        super('ChatRoomService')
    }

    getChatRooms = async () => {
        return await this.chatRoomRepository.repository.find()
    }

    createChatRoom = () => {
        return this.chatRoomRepository.repository.create()
    }
}
