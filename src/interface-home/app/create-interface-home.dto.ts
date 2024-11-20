import { ApiProperty } from '@nestjs/swagger'
import { IsNotEmpty, IsString, IsNumber } from 'class-validator'

export class CreateInterfaceHomeDto {
    @ApiProperty({ description: 'Titre de la page', required: true })
    @IsString()
    @IsNotEmpty()
    page_title: string

    @ApiProperty({ description: 'Texte de la page', required: true })
    @IsString()
    @IsNotEmpty()
    page_text: string

    @ApiProperty({ description: 'Titre de la section 1', required: true })
    @IsString()
    @IsNotEmpty()
    section1_title: string

    @ApiProperty({ description: "ID de l'image associée à la section 1", required: true })
    @IsNumber()
    section1_image: number

    @ApiProperty({ description: 'Texte du bouton de la section 1', required: true })
    @IsString()
    @IsNotEmpty()
    section1_button_text: string

    @ApiProperty({ description: 'Titre de la section 2', required: true })
    @IsString()
    @IsNotEmpty()
    section2_title: string

    @ApiProperty({ description: "ID de l'image associée à la section 2", required: true })
    @IsNumber()
    section2_image: number

    @ApiProperty({ description: 'Texte du bouton de la section 2', required: true })
    @IsString()
    @IsNotEmpty()
    section2_button_text: string

    @ApiProperty({ description: 'Titre de la section 3', required: true })
    @IsString()
    @IsNotEmpty()
    section3_title: string

    @ApiProperty({ description: "ID de l'image associée à la section 3", required: true })
    @IsNumber()
    section3_image: number

    @ApiProperty({ description: 'Texte du bouton de la section 3', required: true })
    @IsString()
    @IsNotEmpty()
    section3_button_text: string
}
