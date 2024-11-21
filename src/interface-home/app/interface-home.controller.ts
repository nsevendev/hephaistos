import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    BadRequestException,
    Put,
} from '@nestjs/common'
import { InterfaceHomeService } from './interface-home.service'
import { CreateInterfaceHomeDto } from './create-interface-home.dto'
import { UpdateInterfaceHomeDto } from './update-interface-home.dto'
import { ApiResponse, ApiTags, ApiBody } from '@nestjs/swagger'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Interface Home Controller')
@Controller('interface-home')
export class InterfaceHomeController {
    constructor(private readonly interfaceHomeService: InterfaceHomeService) {}

    @Post()
    @ApiBody({
        type: CreateInterfaceHomeDto,
        description: 'Données nécessaires pour créer une nouvelle interface home',
    })
    @ApiResponse({ status: 201, description: 'Interface home créée avec succès' })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Les images spécifiées sont introuvables.`,
    })
    async addInterfaceHome(@Body() createInterfaceHomeDto: CreateInterfaceHomeDto) {
        return await this.interfaceHomeService.addInterfaceHome(createInterfaceHomeDto)
    }

    @Get()
    @ApiResponse({
        status: 200,
        description: "Renvoie les données de l'interface home actuelle",
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune interface home trouvée.`,
    })
    async getInterfaceHome() {
        return await this.interfaceHomeService.getInterfaceHome()
    }

    @Put(':id')
    @ApiBody({
        type: UpdateInterfaceHomeDto,
        description: "Données nécessaires pour mettre à jour l'interface home",
    })
    @ApiResponse({
        status: 200,
        description: 'Interface home mise à jour avec succès',
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Interface home introuvable.`,
    })
    async updateInterfaceHome(
        @Param('id') id: number,
        @Body() updateInterfaceHomeDto: UpdateInterfaceHomeDto
    ) {
        return await this.interfaceHomeService.updateInterfaceHome(id, updateInterfaceHomeDto)
    }

    @Delete(':id')
    @ApiResponse({
        status: 200,
        description: 'Interface home supprimée avec succès',
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Interface home introuvable.`,
    })
    async deleteInterfaceHome(@Param('id') id: number) {
        return await this.interfaceHomeService.deleteInterfaceHome(id)
    }
}
