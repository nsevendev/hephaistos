import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsNumber } from 'class-validator'

export class CreateServiceDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'mécanique',
        description: 'Nom du service unique',
    })
    name: string

    @IsNumber()
    @ApiProperty({
        description: "ID de l'utilisateur qui a crée le service",
        type: 'number',
        example: 1,
    })
    created_by: number
}
