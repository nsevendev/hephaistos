import { ApiProperty } from '@nestjs/swagger'
import { IsNotEmpty, IsString } from 'class-validator'

export class CreateInterfaceImageDto {
    @ApiProperty({ description: "URL de l'image sur AWS S3", required: true })
    @IsString()
    @IsNotEmpty()
    url: string

    @ApiProperty({ description: "Clé de l'image dans AWS S3", required: true })
    @IsString()
    @IsNotEmpty()
    aws_key: string
}
