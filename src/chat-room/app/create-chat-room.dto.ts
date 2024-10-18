import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsEmail, IsNotEmpty } from 'class-validator'

export class CreateChatRoomDto {
    @IsEmail()
    @IsNotEmpty()
    @ApiProperty({
        example: 'client@example.com',
        description: "Adresse email de l'utilisateur",
    })
    email: string

    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'John',
        description: "Prénom de l'utilisateur",
    })
    firstname: string

    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'Doe',
        description: "Nom de famille de l'utilisateur",
    })
    lastname: string
}
