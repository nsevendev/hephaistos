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
import { AwsS3Service } from '../../aws/app/aws.service'
import { z } from 'zod'

@ApiTags('Interface Image Controller')
@Controller('interface-image')
export class InterfaceImageController {
    constructor(
        private readonly interfaceImageService: InterfaceImageService,
        private readonly awsS3Service: AwsS3Service
    ) {}

    @Post()
    @ApiBody({
        type: CreateInterfaceImageDto,
        description: 'Données nécessaires pour ajouter une image d’interface',
    })
    @ApiResponse({ status: 201, description: 'Image ajoutée avec succès', type: InterfaceImage })
    async addInterfaceImage(@Body() createInterfaceImageDto: CreateInterfaceImageDto) {
        const fileSchemaValidation = z
            .object({
                buffer: z.instanceof(Buffer).refine((buffer) => buffer.byteLength > 0, {
                    message: 'Le buffer ne peut pas être vide',
                }),
                originalname: z.string().min(1, { message: 'Le nom de fichier est requis' }),
                mimetype: z.string().min(1, { message: 'Le type MIME est requis' }),
                size: z.number().positive({ message: 'La taille doit être positive' }),
            })
            .parse(createInterfaceImageDto)

        const { fileKey } = await this.awsS3Service.uploadFile({ file: fileSchemaValidation })
        const fileUrl = await this.awsS3Service.getFileUrl({ fileKey })

        const newImage = {
            ...createInterfaceImageDto,
            aws_key: fileKey,
            url: fileUrl,
        }

        return this.interfaceImageService.addInterfaceImage(newImage)
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

        for (const image of images) {
            image.url = await this.awsS3Service.getFileUrl({ fileKey: image.aws_key })
        }

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

        const { images } = await this.interfaceImageService.getInterfacesImages(ids)

        for (const image of images) {
            await this.awsS3Service.deleteFile({ fileKey: image.aws_key })
        }

        const { deletedCount } = await this.interfaceImageService.deleteInterfacesImages(ids)
        return {
            deletedCount,
        }
    }
}
