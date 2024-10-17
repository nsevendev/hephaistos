import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty } from 'class-validator'

export class CreateRoleDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'ADMIN',
        description: 'Nom du rôle, par exemple ADMIN ou USER',
    })
    name: string
}
