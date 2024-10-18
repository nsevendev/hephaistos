import { Module } from '@nestjs/common'
import { ChatService } from './app/chat.service'
import { ChatRepository } from './infra/chat.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Chat } from './domaine/chat.entity'
import { ChatRoomModule } from '../chat-room/chat-room.module'

@Module({
    imports: [TypeOrmModule.forFeature([Chat]), ChatRoomModule],
    providers: [ChatService, ChatRepository],
    exports: [ChatService, ChatRepository],
})
export class ChatModule {}
