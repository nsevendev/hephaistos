import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    Put,
    BadRequestException,
    Query,
} from '@nestjs/common'
import { InterfaceImageService } from './interface-image.service'
import { CreateInterfaceImageDto } from './create-interface-image.dto'
import { UpdateInterfaceImageDto } from './update-interface-image.dto'
import { ApiResponse, ApiTags, ApiBody, ApiQuery } from '@nestjs/swagger'
import { InterfaceImage } from '../domaine/interface-image.entity'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Interface Image Controller')
@Controller('interface-image')
export class InterfaceImageController {
    constructor(private readonly interfaceImageService: InterfaceImageService) {}

    @Post()
    @ApiBody({
        type: CreateInterfaceImageDto,
        description: 'Données nécessaires pour ajouter une nouvelle image d’interface',
    })
    @ApiResponse({ status: 201, description: 'Image d’interface ajoutée avec succès', type: InterfaceImage })
    async addInterfaceImage(@Body() createInterfaceImageDto: CreateInterfaceImageDto) {
        return this.interfaceImageService.addInterfaceImage(createInterfaceImageDto)
    }

    @Put(':id')
    @ApiBody({
        type: UpdateInterfaceImageDto,
        description: 'Données nécessaires pour mettre à jour une image d’interface',
    })
    @ApiResponse({
        status: 200,
        description: 'Image d’interface mise à jour avec succès',
        type: InterfaceImage,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => L'image avec l'ID spécifié est introuvable`,
    })
    async updateInterfaceImage(
        @Param('id') id: number,
        @Body() updateInterfaceImageDto: UpdateInterfaceImageDto
    ) {
        return this.interfaceImageService.updateInterfaceImage(id, updateInterfaceImageDto)
    }

    @Get()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs des images d’interface à rechercher',
        required: false,
    })
    @ApiResponse({
        status: 200,
        description: 'Renvoie toutes les images d’interface ou celles correspondant aux IDs fournis',
        type: [InterfaceImage],
    })
    async getInterfacesImages(@Query('ids') ids: number[]) {
        const { images, notFoundCount } = await this.interfaceImageService.getInterfacesImages(ids)

        return {
            images,
            notFoundCount,
        }
    }

    @Delete()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs des images d’interface à supprimer',
        required: true,
    })
    @ApiResponse({ status: 204, description: 'Images d’interface supprimées avec succès' })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni ou tableau d'IDs vide`,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucune image trouvée avec les IDs spécifiés`,
    })
    async deleteInterfacesImages(@Query('ids') ids: number[]) {
        if (!ids || ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }

        const { deletedCount } = await this.interfaceImageService.deleteInterfacesImages(ids)
        return {
            deletedCount,
        }
    }
}
