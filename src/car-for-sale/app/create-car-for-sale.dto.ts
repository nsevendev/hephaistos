import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNumber, IsNotEmpty } from 'class-validator'

export class CreateCarForSaleDto {
    @ApiProperty({ description: 'Fabricant de la voiture', example: 'Toyota' })
    @IsString()
    @IsNotEmpty()
    manufacturer: string

    @ApiProperty({ description: 'Modèle de la voiture', example: 'Corolla' })
    @IsString()
    @IsNotEmpty()
    model: string

    @ApiProperty({ description: 'Prix de la voiture', example: 15000 })
    @IsNumber()
    @IsNotEmpty()
    price: number

    @ApiProperty({ description: 'Puissance de la voiture en chevaux', example: 100 })
    @IsNumber()
    @IsNotEmpty()
    power: number

    @ApiProperty({ description: 'Puissance fiscale de la voiture', example: 7 })
    @IsNumber()
    @IsNotEmpty()
    tax_power: number

    @ApiProperty({ description: 'Type de carburant utilisé', example: 'Essence' })
    @IsString()
    @IsNotEmpty()
    fuel: string

    @ApiProperty({ description: 'Kilométrage de la voiture', example: 12000 })
    @IsNumber()
    @IsNotEmpty()
    mileage: number

    @ApiProperty({ description: 'Type de transmission', example: 'Automatique' })
    @IsString()
    @IsNotEmpty()
    conveyance_type: string

    @ApiProperty({ description: 'Couleur de la voiture', example: 'Noir' })
    @IsString()
    @IsNotEmpty()
    color: string

    @ApiProperty({ description: "ID de l'utilisateur créateur de la voiture", required: false })
    @IsNumber()
    @IsNotEmpty()
    created_by?: number
}
