import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsOptional, IsBoolean } from 'class-validator'

export class UpdateNotificationDto {
    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Your message has been read',
        description: 'Nouveau message de la notification',
        required: false,
    })
    message?: string

    @IsBoolean()
    @IsOptional()
    @ApiProperty({
        example: true,
        description: 'Statut de lecture mis à jour',
        required: false,
    })
    readed?: boolean
}
