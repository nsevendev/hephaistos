import { ApiProperty } from '@nestjs/swagger'
import { IsBoolean, IsOptional } from 'class-validator'

export class UpdateNotificationDto {
    @IsBoolean()
    @IsOptional()
    @ApiProperty({
        example: true,
        description: 'Statut de lecture mis à jour',
        required: false,
    })
    readed?: boolean
}
