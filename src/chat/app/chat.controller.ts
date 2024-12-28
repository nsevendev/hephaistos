import {
    Body,
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
import { ChatService } from './chat.service'
import { CreateChatDto } from './create-chat.dto'
import { UpdateChatDto } from './update-chat.dto'
import { ApiResponse, ApiTags, ApiBody, ApiQuery } from '@nestjs/swagger'
import { Chat } from '../domaine/chat.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Chat Controller')
@Controller('chat')
export class ChatController {
    constructor(private readonly chatService: ChatService) {}

    @Post()
    @ApiBody({ type: CreateChatDto, description: 'Données nécessaires pour créer un nouveau chat' })
    @ApiResponse({ status: 201, description: 'Chat créé avec succès', type: Chat })
    async createChat(@Body() createChatDto: CreateChatDto) {
        return this.chatService.createChat(createChatDto)
    }

    @Get()
    @ApiQuery({
        name: 'ids',
        type: String,
        description: 'Liste des IDs des chats à rechercher, séparés par des virgules',
        required: false,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie tous les chats ou ceux correspondant aux IDs fournis',
        type: [Chat],
    })
    async getAllChats(@Query('ids') ids: string) {
        const idsArray = ids ? ids.split(',').map(Number) : []
        return this.chatService.getChats(idsArray)
    }

    @Get('chatroom/:chatRoomId')
    @ApiResponse({
        status: 200,
        description: 'Renvoie tous les chats pour une chat room spécifique',
        type: [Chat],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun chat trouvé pour la chat room spécifiée`,
    })
    async getChatsByChatRoom(@Param('chatRoomId') chatRoomId: number) {
        return this.chatService.getChatsByChatRoom(chatRoomId)
    }

    @Put(':id')
    @ApiBody({ type: UpdateChatDto, description: 'Données pour mettre à jour un chat' })
    @ApiResponse({ status: 200, description: 'Chat mis à jour avec succès', type: Chat })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun chat trouvé avec cet ID`,
    })
    async updateChat(@Param('id') id: number, @Body() updateChatDto: UpdateChatDto) {
        return this.chatService.updateChat(id, updateChatDto)
    }

    @Delete()
    @ApiQuery({
        name: 'ids',
        type: String,
        description: 'Liste des IDs des chats à supprimer, séparés par des virgules',
        required: true,
    })
    @ApiResponse({ status: 204, description: 'Chats supprimés avec succès' })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni ou tableau d'IDs vide`,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun chat trouvé avec les IDs spécifiés`,
    })
    async deleteChats(@Query('ids') ids: string) {
        if (!ids || ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }
        const idsArray = ids.split(',').map((id) => parseInt(id, 10))
        return this.chatService.deleteChats(idsArray)
    }
}
