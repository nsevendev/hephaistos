import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsNumber } from 'class-validator'

export class CreateMechanicalServiceDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'Changement de pneus',
        description: 'Nom du service mécanique',
    })
    name: string

    @IsNumber()
    @IsNotEmpty()
    @ApiProperty({
        example: 50,
        description: 'Prix minimum pour le service mécanique',
    })
    lower_price: number

    @IsNumber()
    @IsNotEmpty()
    @ApiProperty({
        example: 1,
        description: "ID de l'utilisateur créateur du service",
    })
    created_by: number
}
