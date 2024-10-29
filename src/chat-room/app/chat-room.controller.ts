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
    Query,
} from '@nestjs/common'
import { ChatRoomService } from './chat-room.service'
import { CreateChatRoomDto } from './create-chat-room.dto'
import { UpdateChatRoomDto } from './update-chat-room.dto'
import { ApiResponse, ApiTags, ApiBody, ApiQuery } from '@nestjs/swagger'
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

    @Get(':ids')
    @ApiResponse({
        status: 200,
        description: 'Renvoie la ou les chat rooms correspondant aux IDs fournis',
        type: [ChatRoom],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune chat room trouvée avec ces IDs`,
    })
    async getChatRoomById(@Param('ids') chatRoomIds: string) {
        const idsArray = chatRoomIds.split(',').map(Number)
        return this.chatRoomService.getChatRooms(idsArray)
    }

    @Post('create')
    @ApiBody({
        type: CreateChatRoomDto,
        description: 'Données nécessaires pour créer une nouvelle chat room',
    })
    @ApiResponse({ status: 201, description: 'Renvoie la chat room créée', type: ChatRoom })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Une chat room avec cet email existe déjà`,
    })
    async createChatRoom(@Body() createChatRoomDto: CreateChatRoomDto) {
        return this.chatRoomService.createChatRoom(createChatRoomDto)
    }

    @Put(':id')
    @ApiBody({ type: UpdateChatRoomDto, description: 'Données pour mettre à jour une chat room' })
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
    @ApiQuery({
        name: 'ids',
        type: String,
        description: 'Liste des IDs des chat rooms à supprimer, séparés par des virgules',
        required: true,
    })
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
    async deleteChatRooms(@Query('ids') ids: string) {
        if (!ids || ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }
        const idsArray = ids.split(',').map((id) => parseInt(id, 10))
        return this.chatRoomService.deleteChatRooms(idsArray)
    }
}
