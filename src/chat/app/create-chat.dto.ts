import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsEnum, IsBoolean, IsNotEmpty, IsNumber } from 'class-validator'
import { SenderType } from '../domaine/chat.entity'

export class CreateChatDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: "Bonjour, j'ai des questions sur la reprogrammation automobile",
        description: 'Le contenu du message',
    })
    message: string

    @IsEnum(SenderType)
    @IsNotEmpty()
    @ApiProperty({
        example: 'garage',
        description: "L'expéditeur du message (garage ou client)",
        enum: SenderType,
    })
    sender: SenderType

    @IsBoolean()
    @ApiProperty({
        example: false,
        description: 'Indique si le message a été lu',
        default: false,
    })
    readed: boolean

    @IsNumber()
    @IsNotEmpty()
    @ApiProperty({
        example: 1,
        description: "L'ID de la ChatRoom à laquelle ce message est lié",
    })
    room: number
}
