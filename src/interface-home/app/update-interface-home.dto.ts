import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsOptional, IsNumber } from 'class-validator'

export class UpdateInterfaceHomeDto {
    @ApiProperty({ description: 'Titre de la page', required: false })
    @IsString()
    @IsOptional()
    page_title?: string

    @ApiProperty({ description: 'Texte de la page', required: false })
    @IsString()
    @IsOptional()
    page_text?: string

    @ApiProperty({ description: 'Titre de la section 1', required: false })
    @IsString()
    @IsOptional()
    section1_title?: string

    @ApiProperty({ description: "ID de l'image associée à la section 1", required: false })
    @IsNumber()
    @IsOptional()
    section1_image?: number

    @ApiProperty({ description: 'Texte du bouton de la section 1', required: false })
    @IsString()
    @IsOptional()
    section1_button_text?: string

    @ApiProperty({ description: 'Titre de la section 2', required: false })
    @IsString()
    @IsOptional()
    section2_title?: string

    @ApiProperty({ description: "ID de l'image associée à la section 2", required: false })
    @IsNumber()
    @IsOptional()
    section2_image?: number

    @ApiProperty({ description: 'Texte du bouton de la section 2', required: false })
    @IsString()
    @IsOptional()
    section2_button_text?: string

    @ApiProperty({ description: 'Titre de la section 3', required: false })
    @IsString()
    @IsOptional()
    section3_title?: string

    @ApiProperty({ description: "ID de l'image associée à la section 3", required: false })
    @IsNumber()
    @IsOptional()
    section3_image?: number

    @ApiProperty({ description: 'Texte du bouton de la section 3', required: false })
    @IsString()
    @IsOptional()
    section3_button_text?: string
}
