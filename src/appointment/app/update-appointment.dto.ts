import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsOptional, IsDate, IsNumber } from 'class-validator'
import { Type } from 'class-transformer'

export class UpdateAppointmentDto {
    @IsDate()
    @IsOptional()
    @Type(() => Date)
    @ApiProperty({
        description: "Date de début de l'appointment",
        example: '2024-10-25T09:00:00Z',
        required: false,
    })
    appointment_start?: Date

    @IsDate()
    @IsOptional()
    @Type(() => Date)
    @ApiProperty({
        description: "Date de fin de l'appointment",
        example: '2024-10-25T10:00:00Z',
        required: false,
    })
    appointment_end?: Date

    @IsNumber()
    @IsOptional()
    @ApiProperty({
        description: "ID du service lié à l'appointment",
        example: 1,
        required: false,
    })
    service_id?: number

    @IsNumber()
    @IsOptional()
    @ApiProperty({
        description: "ID du service mécanique lié à l'appointment (optionnel)",
        example: 2,
        required: false,
    })
    mechanical_service_id?: number

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Constructeur du véhicule (optionnel)',
        example: 'Toyota',
        required: false,
    })
    car_manufacturer?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Modèle du véhicule (optionnel)',
        example: 'Corolla',
        required: false,
    })
    car_model?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Année du véhicule (optionnel)',
        example: '2020',
        required: false,
    })
    car_year?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Motorisation du véhicule (optionnel)',
        example: 'Hybride',
        required: false,
    })
    car_motorisation?: string
}
