import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsOptional, IsEmail, IsPhoneNumber, IsNumber } from 'class-validator'

export class UpdateContactDto {
    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'John',
        description: 'Prénom du contact',
        required: false,
    })
    firstname?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Doe',
        description: 'Nom du contact',
        required: false,
    })
    lastname?: string

    @IsEmail()
    @IsOptional()
    @ApiProperty({
        example: 'john.doe@example.com',
        description: 'Email du contact',
        required: false,
    })
    email?: string

    @IsPhoneNumber(null)
    @IsOptional()
    @ApiProperty({
        example: '+123456789',
        description: 'Numéro de téléphone du contact',
        required: false,
    })
    phone?: string

    @IsNumber()
    @IsOptional()
    @ApiProperty({
        description: 'ID du service lié au contact',
        example: 1,
        required: false,
    })
    service_id?: number

    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Diesel',
        description: 'Motorisation du véhicule',
        required: false,
    })
    car_motorisation?: string

    @IsNumber()
    @IsOptional()
    @ApiProperty({
        example: 2020,
        description: 'Année du véhicule',
        required: false,
    })
    car_year?: number

    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Model S',
        description: 'Modèle du véhicule',
        required: false,
    })
    car_model?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Tesla',
        description: 'Constructeur du véhicule',
        required: false,
    })
    car_manufacturer?: string

    @IsNumber()
    @IsOptional()
    @ApiProperty({
        description: 'ID du service mécanique lié au contact',
        example: 1,
        type: 'number',
        required: false,
    })
    mechanical_service_id?: number

    @IsString()
    @IsOptional()
    @ApiProperty({
        example: 'Je souhaite une révision complète du véhicule.',
        description: 'Message du contact',
        required: false,
    })
    message?: string
}
