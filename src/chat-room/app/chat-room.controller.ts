import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    BadRequestException,
    Query,
    Put,
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
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs de chat rooms à rechercher',
        required: false,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description:
            "Renvoie les chat rooms correspondant aux IDs fournis ou toutes les chat rooms si aucun ID n'est fourni",
        type: [ChatRoom],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune chat room trouvée avec les IDs spécifiés`,
    })
    async getChatRooms(@Query('ids') ids: number[]) {
        const chatRooms = await this.chatRoomService.getChatRooms(ids)
        if (!chatRooms || chatRooms.length === 0) {
            throw new NotFoundException(`Aucune chat room trouvée.`)
        }
        return chatRooms
    }

    @Post('create')
    @ApiBody({
        type: CreateChatRoomDto,
        description: 'Données nécessaires pour créer une nouvelle chat room',
    })
    @ApiResponse({
        status: 201,
        description: 'Renvoie la chat room créée',
        type: ChatRoom,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Si les données fournies sont invalides`,
    })
    async createChatRoom(@Body() createChatRoomDto: CreateChatRoomDto) {
        return this.chatRoomService.createChatRoom(createChatRoomDto)
    }

    @Put('update/:id')
    @ApiBody({
        type: UpdateChatRoomDto,
        description: 'Données pour mettre à jour une chat room',
    })
    @ApiResponse({
        status: 200,
        description: 'Chat room mise à jour avec succès',
        type: ChatRoom,
    })
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
        type: [Number],
        description: 'Liste des IDs des chat rooms à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({
        status: 204,
        description: 'Chat rooms supprimées avec succès',
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune chat room trouvée avec les IDs fournis`,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni pour la suppression`,
    })
    async deleteChatRooms(@Query('ids') ids: number[]) {
        if (!ids || ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }
        return this.chatRoomService.deleteChatRooms(ids)
    }
}
