import { Module } from '@nestjs/common'
import { ChatRoomService } from './app/chat-room.service'
import { ChatRoomRepository } from './infra/chat-room.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { ChatRoom } from './domaine/chat-room.entity'

@Module({
    imports: [TypeOrmModule.forFeature([ChatRoom])],
    providers: [ChatRoomService, ChatRoomRepository],
    exports: [ChatRoomService, ChatRoomRepository],
})
export class ChatRoomModule {}
