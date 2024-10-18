import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsOptional } from 'class-validator'

export class UpdateChatRoomDto {
    @IsOptional()
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'Jane',
        description: "Prénom de l'utilisateur (optionnel)",
    })
    firstname?: string

    @IsOptional()
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'Doe',
        description: "Nom de famille de l'utilisateur (optionnel)",
    })
    lastname?: string

    @IsOptional()
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'A1B2C3D4',
        description: "Code d'accès unique pour la salle de chat (optionnel)",
    })
    access_code?: string
}
