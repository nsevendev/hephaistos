import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsBoolean } from 'class-validator'

export class CreateNotificationDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'You have a new message',
        description: 'Message de la notification',
    })
    message: string

    @IsBoolean()
    @ApiProperty({
        example: false,
        description: 'Statut de lecture de la notification',
        default: false,
    })
    readed: boolean
}
