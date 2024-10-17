import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsEmail, IsOptional, IsNumber, IsPhoneNumber } from 'class-validator'

export class CreateContactDto {
    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'John',
        description: 'Prénom du contact',
    })
    firstname: string

    @IsString()
    @IsNotEmpty()
    @ApiProperty({
        example: 'Doe',
        description: 'Nom du contact',
    })
    lastname: string

    @IsEmail()
    @IsNotEmpty()
    @ApiProperty({
        example: 'john.doe@example.com',
        description: 'Email du contact',
    })
    email: string

    @IsPhoneNumber(null)
    @IsOptional()
    @ApiProperty({
        example: '+123456789',
        description: 'Numéro de téléphone du contact',
        required: false,
    })
    phone?: string

    @IsNumber()
    @IsNotEmpty()
    @ApiProperty({
        description: 'ID du service lié au contact',
        example: 1,
        type: 'number',
    })
    service_id: number

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
    @IsNotEmpty()
    @ApiProperty({
        example: 'Model S',
        description: 'Modèle du véhicule',
    })
    car_model: string

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
    @IsNotEmpty()
    @ApiProperty({
        example: 'Je souhaite une révision complète du véhicule.',
        description: 'Message du contact',
    })
    message: string
}
