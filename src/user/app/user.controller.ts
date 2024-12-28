import {
    Body,
    ConflictException,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Put,
    Query,
} from '@nestjs/common'
import { UserService } from './user.service'
import { CreateUserDto } from './create-user.dto'
import { UpdateUserDto } from './update-user.dto'
import { ApiBody, ApiParam, ApiQuery, ApiResponse, ApiTags } from '@nestjs/swagger'
import { User } from '../domaine/user.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('User Controller')
@Controller('user')
export class UserController {
    constructor(private readonly userService: UserService) {}

    @Get()
    @ApiQuery({
        name: 'userIds',
        type: [Number],
        description: 'Liste des IDs des utilisateurs à rechercher',
        required: true,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie les utilisateurs correspondant aux IDs fournis',
        type: [User],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun rôle trouvé avec ces IDs`,
    })
    async getUsers(@Query('userIds') userIds: number[]) {
        return this.userService.getUsers(userIds)
    }

    @Post('create')
    @ApiBody({ type: CreateUserDto, description: 'Données nécessaires pour créer un nouvel utilisateur' })
    @ApiResponse({ status: 200, description: "Renvoie l'utilisateur créé", type: User })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Un utilisateur avec cet email ou username existe déjà.`,
    })
    async createUser(@Body() createUserDto: CreateUserDto): Promise<User> {
        return this.userService.createUser(createUserDto)
    }

    @Put(':id')
    @ApiParam({ name: 'id', type: Number, description: "ID de l'utilisateur à mettre à jour" })
    @ApiBody({ type: UpdateUserDto, description: 'Données nécessaires pour mettre à jour un utilisateur' })
    @ApiResponse({ status: 200, description: "Renvoie l'utilisateur mis à jour", type: User })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun utilisateur trouvé avec cet ID`,
    })
    async updateUser(@Param('id') userId: number, @Body() updateUserDto: UpdateUserDto): Promise<User> {
        return this.userService.updateUser(userId, updateUserDto)
    }

    @Delete()
    @ApiQuery({
        name: 'userIds',
        type: [Number],
        description: 'Liste des IDs des utilisateurs à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({ status: 204, description: 'Utilisateurs supprimés avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun utilisateur trouvé avec les IDs fournis`,
    })
    async deleteUsers(@Query('userIds') userIds: number[]): Promise<void> {
        await this.userService.deleteUsers(userIds)
    }
}
