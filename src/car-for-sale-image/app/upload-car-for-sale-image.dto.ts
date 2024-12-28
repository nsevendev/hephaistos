import { ApiProperty } from '@nestjs/swagger'
import { IsNotEmpty, IsNumber, IsString } from 'class-validator'

export class UploadCarForSaleImageDto {
    @ApiProperty({ description: 'ID de la voiture associée', required: true })
    @IsNumber()
    @IsNotEmpty()
    car_for_sale: number

    @ApiProperty({ description: 'Buffer de l’image', required: true })
    @IsNotEmpty()
    buffer: Buffer

    @ApiProperty({ description: 'Nom original du fichier', required: true })
    @IsString()
    @IsNotEmpty()
    originalname: string

    @ApiProperty({ description: 'Type MIME', required: true })
    @IsString()
    @IsNotEmpty()
    mimetype: string

    @ApiProperty({ description: 'Taille du fichier en octets', required: true })
    @IsNumber()
    @IsNotEmpty()
    size: number

    @ApiProperty({ description: "ID de l'utilisateur ayant créé l'image", required: true })
    @IsNumber()
    @IsNotEmpty()
    created_by: number
}
