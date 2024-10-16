import {
    Body,
    ConflictException,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Put,
    BadRequestException,
} from '@nestjs/common'
import { ChatRoomService } from './chat-room.service'
import { CreateChatRoomDto } from './create-chat-room.dto'
import { UpdateChatRoomDto } from './update-chat-room.dto'
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { ChatRoom } from '../domaine/chat-room.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('ChatRoom Controller')
@Controller('chatroom')
export class ChatRoomController {
    constructor(private readonly chatRoomService: ChatRoomService) {}

    @Get()
    @ApiResponse({ status: 200, description: 'Renvoie toutes les chat rooms', type: [ChatRoom] })
    async getAllChatRooms() {
        return this.chatRoomService.getChatRooms([])
    }

    @Get(':id')
    @ApiResponse({ status: 200, description: "Renvoie la chat room correspondant à l'ID", type: ChatRoom })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune chat room trouvée avec cet ID`,
    })
    async getChatRoomById(@Param('id') chatRoomId: number) {
        return this.chatRoomService.getChatRooms([chatRoomId])
    }

    @Post('create')
    @ApiResponse({ status: 200, description: 'Renvoie la chat room créée', type: ChatRoom })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Une chat room avec cet email existe déjà`,
    })
    async createChatRoom(@Body() createChatRoomDto: CreateChatRoomDto) {
        return this.chatRoomService.createChatRoom(createChatRoomDto)
    }

    @Put(':id')
    @ApiResponse({ status: 200, description: 'Renvoie la chat room mise à jour', type: ChatRoom })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune chat room trouvée avec cet ID`,
    })
    async updateChatRoom(@Param('id') chatRoomId: number, @Body() updateChatRoomDto: UpdateChatRoomDto) {
        return this.chatRoomService.updateChatRoom(chatRoomId, updateChatRoomDto)
    }

    @Delete()
    @ApiResponse({ status: 204, description: 'Chat rooms supprimées avec succès' })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni ou tableau d'IDs vide`,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune chat room trouvée avec les IDs fournis`,
    })
    async deleteChatRooms(@Body('ids') ids: number[]) {
        return this.chatRoomService.deleteChatRooms(ids)
    }
}
