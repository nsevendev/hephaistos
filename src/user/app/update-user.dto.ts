import { ApiProperty, PartialType } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsOptional, IsEmail, MinLength, IsInt } from 'class-validator'
import { CreateUserDto } from './create-user.dto'

export class UpdateUserDto extends PartialType(CreateUserDto) {
    @IsOptional()
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'new_username',
        description: "Nom d'utilisateur de l'utilisateur",
        required: false,
    })
    username?: string

    @IsOptional()
    @IsEmail()
    @ApiProperty({
        example: 'newemail@example.com',
        description: "Email de l'utilisateur",
        required: false,
    })
    email?: string

    @IsOptional()
    @IsString()
    @MinLength(6)
    @ApiProperty({
        example: 'new_password123',
        description: 'Nouveau mot de passe',
        required: false,
    })
    password?: string

    @IsOptional()
    @IsInt()
    @ApiProperty({
        example: 2,
        description: 'ID du rôle de l’utilisateur',
        required: false,
    })
    role?: number
}
