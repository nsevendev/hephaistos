import {
    Body,
    ConflictException,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
} from '@nestjs/common'
import { RoleService } from './role.service'
import { CreateRoleDto } from './create-role.dto'
import { ApiResponse, ApiTags } from '@nestjs/swagger'
import { Role } from '../domaine/role.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Role Controller')
@Controller('role')
export class RoleController {
    constructor(private readonly roleService: RoleService) {}

    @Post('create')
    @ApiResponse({ status: 200, description: 'Renvoie le rôle créé', type: Role })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Si un rôle existe déjà, impossible de créer un rôle`,
    })
    async createRole(@Body() createRoleDto: CreateRoleDto) {
        return this.roleService.createRole(createRoleDto)
    }

    @Get()
    @ApiResponse({ status: 200, description: 'Renvoie tous les rôles', type: [Role] })
    async getAllRoles() {
        return this.roleService.getManyRoles()
    }

    @Get(':id')
    @ApiResponse({ status: 200, description: "Renvoie le rôle correspondant à l'ID", type: Role })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun rôle trouvé avec cet ID`,
    })
    async getRoleById(@Param('id') roleId: number) {
        return this.roleService.getOneRole(roleId)
    }

    @Delete(':id')
    @ApiResponse({ status: 204, description: 'Rôle supprimé avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun rôle trouvé avec cet ID`,
    })
    async deleteRole(@Param('id') roleId: number) {
        await this.roleService.deleteOneRole(roleId)
        return
    }

    @Delete()
    @ApiResponse({ status: 204, description: 'Rôles supprimés avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun rôle trouvé pour les IDs fournis`,
    })
    async deleteManyRoles(@Body() roleIds: number[]) {
        await this.roleService.deleteManyRoles(roleIds)
        return
    }
}
