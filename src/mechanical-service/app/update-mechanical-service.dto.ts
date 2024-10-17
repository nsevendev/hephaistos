import { ApiProperty, PartialType } from '@nestjs/swagger'
import { IsString, IsNumber, IsOptional } from 'class-validator'
import { CreateMechanicalServiceDto } from './create-mechanical-service.dto'

export class UpdateMechanicalServiceDto extends PartialType(CreateMechanicalServiceDto) {
    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Révision complète',
        description: 'Nom du service mécanique (optionnel)',
        required: false,
    })
    name?: string

    @IsNumber()
    @IsOptional()
    @ApiProperty({
        example: 100,
        description: 'Prix minimum du service mécanique (optionnel)',
        required: false,
    })
    lower_price?: number
}
