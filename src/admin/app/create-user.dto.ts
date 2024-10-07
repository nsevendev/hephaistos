import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsEmail, IsNotEmpty, IsNumber } from 'class-validator'

export class CreateUserDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'johndoe',
        description: "Nom d'utilisateur unique",
    })
    username: string

    @IsEmail()
    @IsNotEmpty()
    @ApiProperty({
        example: 'johndoe@example.com',
        description: "Adresse email de l'utilisateur",
    })
    email: string

    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'password123',
        description: "Mot de passe de l'utilisateur",
    })
    password: string

    @IsNumber()
    @ApiProperty({
        description: "ID du rôle associé à l'utilisateur",
        type: 'number',
        example: 1,
    })
    roleId: number
}
