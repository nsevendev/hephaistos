import {
    Body,
    ConflictException,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Post,
    Query,
} from '@nestjs/common'
import { RoleService } from './role.service'
import { CreateRoleDto } from './create-role.dto'
import { ApiBody, ApiQuery, ApiResponse, ApiTags } from '@nestjs/swagger'
import { Role } from '../domaine/role.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Role Controller')
@Controller('role')
export class RoleController {
    constructor(private readonly roleService: RoleService) {}

    @Post('create')
    @ApiBody({ type: CreateRoleDto, description: 'Données nécessaires pour créer un nouveau rôle' })
    @ApiResponse({ status: 200, description: 'Renvoie le rôle créé', type: Role })
    @ApiResponse({
        status: 409,
        type: HttpExceptionResponse,
        description: `${ConflictException.name} => Si un rôle existe déjà, impossible de créer le rôle`,
    })
    async createRole(@Body() createRoleDto: CreateRoleDto) {
        return this.roleService.createRole(createRoleDto)
    }

    @Get()
    @ApiQuery({
        name: 'roleIds',
        type: [Number],
        description: 'Liste des IDs de rôles à rechercher',
        required: true,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie les rôles correspondant aux IDs fournis',
        type: [Role],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun rôle trouvé avec ces IDs`,
    })
    async getRoles(@Query('roleIds') roleIds: number[]) {
        return this.roleService.getRoles(roleIds)
    }

    @Delete()
    @ApiQuery({
        name: 'roleIds',
        type: [Number],
        description: 'Liste des IDs de rôles à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({ status: 204, description: 'Rôles supprimés avec succès' })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun rôle trouvé pour les IDs fournis`,
    })
    async deleteRoles(@Query('roleIds') roleIds: number[]) {
        return this.roleService.deleteRoles(roleIds)
    }
}
