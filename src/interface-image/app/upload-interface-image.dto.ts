import { ApiProperty } from '@nestjs/swagger'
import { IsNotEmpty, IsNumber, IsString } from 'class-validator'

export class UploadInterfaceImageDto {
    @ApiProperty({ description: "URL de l'image", required: true })
    @IsString()
    @IsNotEmpty()
    url: string

    @ApiProperty({ description: "Clé AWS associée à l'image", required: true })
    @IsString()
    @IsNotEmpty()
    aws_key: string

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
}
