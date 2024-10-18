import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNumber, IsOptional } from 'class-validator'

export class UpdateCarForSaleDto {
    @ApiProperty({ description: 'Fabricant de la voiture', example: 'Toyota', required: false })
    @IsOptional()
    @IsString()
    manufacturer?: string

    @ApiProperty({ description: 'Modèle de la voiture', example: 'Corolla', required: false })
    @IsOptional()
    @IsString()
    model?: string

    @ApiProperty({ description: 'Prix de la voiture', example: 15000, required: false })
    @IsOptional()
    @IsNumber()
    price?: number

    @ApiProperty({ description: 'Puissance de la voiture en chevaux', example: 120, required: false })
    @IsOptional()
    @IsNumber()
    power?: number

    @ApiProperty({ description: 'Puissance fiscale de la voiture', example: 6, required: false })
    @IsOptional()
    @IsNumber()
    tax_power?: number

    @ApiProperty({ description: 'Type de carburant utilisé', example: 'Essence', required: false })
    @IsOptional()
    @IsString()
    fuel?: string

    @ApiProperty({ description: 'Kilométrage de la voiture', example: 130000, required: false })
    @IsOptional()
    @IsNumber()
    mileage?: number

    @ApiProperty({ description: 'Type de transmission', example: 'Automatique', required: false })
    @IsOptional()
    @IsString()
    conveyance_type?: string

    @ApiProperty({ description: 'Couleur de la voiture', example: 'Noir', required: false })
    @IsOptional()
    @IsString()
    color?: string
}
