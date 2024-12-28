import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsOptional } from 'class-validator'

export class UpdateChatDto {
    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'I would like to update my previous message.',
        description: 'Le contenu du message à mettre à jour',
    })
    message?: string
}
