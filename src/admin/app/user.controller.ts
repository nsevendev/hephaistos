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
} from '@nestjs/common'
import { UserService } from './user.service'
import { CreateUserDto } from './create-user.dto'
import { UpdateUserDto } from './update-user.dto'
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { User } from '../domaine/user.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('User Controller')
@Controller('user')
export class UserController {
    constructor(private readonly userService: UserService) {}

    @Post('create')
    @ApiResponse({ status: 200, description: "Renvoie l'utilisateur créé", type: User })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Si un utilisateur avec cet email ou username existe déjà, impossible de créer un utilisateur`,
    })
    async createUser(@Body() createUserDto: CreateUserDto) {
        return this.userService.createUser(createUserDto)
    }

    @Get()
    @ApiResponse({ status: 200, description: 'Renvoie tous les utilisateurs', type: [User] })
    async getAllUsers() {
        return this.userService.getUsers()
    }

    @Get(':id')
    @ApiResponse({ status: 200, description: "Renvoie l'utilisateur correspondant à l'ID", type: User })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun utilisateur trouvé avec cet ID`,
    })
    async getUserById(@Param('id') userId: number) {
        return this.userService.getUser(userId)
    }

    @Put(':id')
    @ApiResponse({ status: 200, description: "Renvoie l'utilisateur mis à jour", type: User })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun utilisateur trouvé avec cet ID`,
    })
    async updateUser(@Param('id') userId: number, @Body() updateUserDto: UpdateUserDto) {
        return this.userService.updateUser(userId, updateUserDto)
    }

    @Delete(':id')
    @ApiResponse({ status: 204, description: 'Utilisateur supprimé avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun utilisateur trouvé avec cet ID`,
    })
    async deleteUser(@Param('id') userId: number) {
        await this.userService.deleteUser(userId)
        return
    }
}
