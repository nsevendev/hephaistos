import {
    Body,
    Controller,
    Delete,
    Get,
    NotFoundException,
    Param,
    Post,
    BadRequestException,
    Query,
    Put,
} from '@nestjs/common'
import { ContactService } from './contact.service'
import { ApiResponse, ApiTags, ApiBody, ApiQuery } from '@nestjs/swagger'
import { Contact } from '../domaine/contact.entity'
import { CreateContactDto } from './create-contact.dto'
import { UpdateContactDto } from './update-contact.dto'
import { HttpExceptionResponse } from '../../shared/exception-response/http-exception-response'

@ApiTags('Contact Controller')
@Controller('contact')
export class ContactController {
    constructor(private readonly contactService: ContactService) {}

    @Get()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs de contacts à rechercher',
        required: false,
        isArray: true,
    })
    @ApiResponse({
        status: 200,
        description:
            "Renvoie les contacts correspondant aux IDs fournis ou tous les contacts si aucun ID n'est fourni",
        type: [Contact],
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun contact trouvé avec les IDs spécifiés`,
    })
    async getContact(@Query('ids') ids: number[]) {
        const contacts = await this.contactService.getContact(ids)
        if (!contacts || contacts.length === 0) {
            throw new NotFoundException(`Aucun contact trouvé.`)
        }
        return contacts
    }

    @Post('create')
    @ApiBody({ type: CreateContactDto, description: 'Données nécessaires pour créer un nouveau contact' })
    @ApiResponse({
        status: 201,
        description: 'Renvoie le contact créé',
        type: Contact,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Si les données fournies sont invalides`,
    })
    async createContact(@Body() createContactDto: CreateContactDto) {
        return this.contactService.createContact(createContactDto)
    }

    @Put('update/:id')
    @ApiBody({ type: UpdateContactDto, description: 'Données pour mettre à jour un contact' })
    @ApiResponse({
        status: 200,
        description: 'Contact mis à jour avec succès',
        type: Contact,
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun contact correspondant à cet ID n'a été trouvé`,
    })
    async updateContact(@Param('id') id: number, @Body() updateContactDto: UpdateContactDto) {
        return this.contactService.updateContact(id, updateContactDto)
    }

    @Delete()
    @ApiQuery({
        name: 'ids',
        type: [Number],
        description: 'Liste des IDs de contacts à supprimer',
        required: true,
        isArray: true,
    })
    @ApiResponse({
        status: 204,
        description: 'Contacts supprimés avec succès',
    })
    @ApiResponse({
        status: 404,
        type: HttpExceptionResponse,
        description: `${NotFoundException.name} => Aucun contact trouvé avec les IDs fournis`,
    })
    @ApiResponse({
        status: 400,
        type: HttpExceptionResponse,
        description: `${BadRequestException.name} => Aucun ID fourni pour la suppression`,
    })
    async deleteContact(@Query('ids') ids: number[]) {
        if (!ids || ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }
        return this.contactService.deleteContact(ids)
    }
}
