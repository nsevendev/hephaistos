import { Module } from '@nestjs/common'
import { ChatRoomService } from './app/chat-room.service'
import { ChatRoomRepository } from './infra/chat-room.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { ChatRoom } from './domaine/chat-room.entity'
import { ChatRoomController } from './app/chat-room.controller'

@Module({
    imports: [TypeOrmModule.forFeature([ChatRoom])],
    providers: [ChatRoomService, ChatRoomRepository],
    exports: [ChatRoomService, ChatRoomRepository],
    controllers: [ChatRoomController],
})
export class ChatRoomModule {}