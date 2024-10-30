import { ApiProperty } from '@nestjs/swagger'
import { IsNotEmpty, IsNumber, IsString } from 'class-validator'

export class CreateCarForSaleImageDto {
    @ApiProperty({ description: 'ID de la voiture associée', required: true })
    @IsNumber()
    @IsNotEmpty()
    car_for_sale: number

    @ApiProperty({ description: "URL de l'image sur AWS S3", required: true })
    @IsString()
    @IsNotEmpty()
    url?: string

    @ApiProperty({ description: "Clé de l'image dans AWS S3", required: true })
    @IsString()
    @IsNotEmpty()
    aws_key?: string

    @ApiProperty({ description: "ID de l'utilisateur qui a créé l'image", required: true })
    @IsNumber()
    @IsNotEmpty()
    created_by: number
}
