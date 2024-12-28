import { ApiProperty } from '@nestjs/swagger'
import { IsOptional, IsString } from 'class-validator'

export class UpdateInterfaceImageDto {
    @ApiProperty({ description: "URL de l'image sur AWS S3", required: false })
    @IsString()
    @IsOptional()
    url?: string

    @ApiProperty({ description: "Clé de l'image dans AWS S3", required: false })
    @IsString()
    @IsOptional()
    aws_key?: string
}
