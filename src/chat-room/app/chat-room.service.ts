import { Injectable, NotFoundException, BadRequestException, ConflictException } from '@nestjs/common'
import { ChatRoomRepository } from '../infra/chat-room.repository'
import { CreateChatRoomDto } from './create-chat-room.dto'
import { UpdateChatRoomDto } from './update-chat-room.dto'
import { In } from 'typeorm'

@Injectable()
export class ChatRoomService {
    constructor(private readonly chatRoomRepository: ChatRoomRepository) {}

    getChatRooms = async (ids: number[]) => {
        if (ids.length === 0) {
            return await this.chatRoomRepository.repository.find()
        }

        const chatRooms = await this.chatRoomRepository.repository.find({
            where: { id: In(ids) },
        })

        if (chatRooms.length === 0) {
            throw new NotFoundException('Aucune chat room trouvée avec les IDs spécifiés.')
        }

        return chatRooms
    }

    createChatRoom = async (createChatRoomDto: CreateChatRoomDto) => {
        const existingChatRoom = await this.chatRoomRepository.repository.findOne({
            where: { email: createChatRoomDto.email },
        })

        if (existingChatRoom) {
            throw new ConflictException(`Une chat room existe déjà pour l'email ${createChatRoomDto.email}.`)
        }

        return await this.chatRoomRepository.createChatRoom(createChatRoomDto)
    }

    updateChatRoom = async (id: number, updateChatRoomDto: UpdateChatRoomDto) => {
        const existingChatRoom = await this.chatRoomRepository.repository.findOne({ where: { id } })

        if (!existingChatRoom) {
            throw new NotFoundException(`Chat room avec l'ID ${id} introuvable.`)
        }

        const updatedChatRoom = {
            ...existingChatRoom,
            ...updateChatRoomDto,
        }

        return await this.chatRoomRepository.repository.save(updatedChatRoom)
    }

    deleteChatRooms = async (ids: number[]) => {
        if (ids.length === 0) {
            throw new BadRequestException("Le tableau d'IDs ne peut pas être vide.")
        }

        const existingChatRooms = await this.chatRoomRepository.repository.find({
            where: { id: In(ids) },
        })

        const existingIds = existingChatRooms.map((room) => room.id)

        const notFoundIds = ids.filter((id) => !existingIds.includes(id))

        if (existingChatRooms.length === 0) {
            throw new NotFoundException(`Aucune chat room trouvée pour les IDs spécifiés : ${ids.join(', ')}`)
        }

        await this.chatRoomRepository.repository.remove(existingChatRooms)

        return {
            message: `Suppression réussie. ${existingChatRooms.length} chat rooms supprimées.`,
            notFoundIds: notFoundIds.length > 0 ? notFoundIds : null,
        }
    }
}
