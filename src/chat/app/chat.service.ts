import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { ChatRepository } from '../infra/chat.repository'
import { ChatRoomRepository } from '../../chat-room/infra/chat-room.repository'
import { CreateChatDto } from './create-chat.dto'
import { UpdateChatDto } from './update-chat.dto'
import { In } from 'typeorm'
import { Chat } from '../domaine/chat.entity'

@Injectable()
export class ChatService {
    constructor(
        private readonly chatRepository: ChatRepository,
        private readonly chatRoomRepository: ChatRoomRepository
    ) {}

    createChat = async (createChatDto: CreateChatDto) => {
        const { room, ...otherChatData } = createChatDto

        const chatRoom = await this.chatRoomRepository.repository.findOne({
            where: { id: room },
        })

        if (!chatRoom) {
            throw new NotFoundException(`Chat room avec l'ID ${room} introuvable.`)
        }

        const chat: Chat = this.chatRepository.repository.create({
            ...otherChatData,
            room: chatRoom,
        })

        return await this.chatRepository.repository.save(chat)
    }

    getChats = async (ids: number[]) => {
        if (ids.length === 0) {
            return await this.chatRepository.repository.find()
        }

        const chats = await this.chatRepository.repository.find({
            where: { id: In(ids) },
        })

        if (chats.length === 0) {
            throw new NotFoundException('Aucun chat trouvé avec les IDs spécifiés.')
        }

        return chats
    }

    getChatsByChatRoom = async (chatRoomId: number) => {
        const chatRoom = await this.chatRoomRepository.repository.findOne({
            where: { id: chatRoomId },
        })

        if (!chatRoom) {
            throw new NotFoundException(`Chat room avec l'ID ${chatRoomId} introuvable.`)
        }

        const chats = await this.chatRepository.repository.find({
            where: { room: { id: chatRoomId } },
        })
        return chats
    }

    updateChat = async (id: number, updateChatDto: UpdateChatDto) => {
        const existingChat = await this.chatRepository.repository.findOne({
            where: { id },
            relations: ['room'],
        })

        if (!existingChat) {
            throw new NotFoundException(`Chat avec l'ID ${id} introuvable.`)
        }

        existingChat.message = updateChatDto.message

        return await this.chatRepository.repository.save(existingChat)
    }

    deleteChats = async (ids: number[]) => {
        if (ids.length === 0) {
            throw new BadRequestException("Le tableau d'IDs ne peut pas être vide.")
        }

        const existingChats = await this.chatRepository.repository.find({
            where: { id: In(ids) },
        })

        const existingIds = existingChats.map((chat) => chat.id)
        const notFoundIds = ids.filter((id) => !existingIds.includes(id))

        if (existingChats.length === 0) {
            throw new NotFoundException(`Aucun chat trouvé pour les IDs spécifiés : ${ids.join(', ')}`)
        }

        await this.chatRepository.repository.remove(existingChats)

        return {
            message: `Suppression réussie. ${existingChats.length} chats supprimés.`,
            notFoundIds: notFoundIds.length > 0 ? notFoundIds : null,
        }
    }
}
