import { ApiProperty } from '@nestjs/swagger'
import { IsString, IsNotEmpty, IsOptional, IsDate } from 'class-validator'
import { Type } from 'class-transformer'

export class CreateAppointmentDto {
    @IsDate()
    @IsNotEmpty()
    @Type(() => Date)
    @ApiProperty({
        description: "Date de début de l'appointment",
        example: '2024-10-25T09:00:00Z',
    })
    appointment_start: Date

    @IsDate()
    @IsNotEmpty()
    @Type(() => Date)
    @ApiProperty({
        description: "Date de fin de l'appointment",
        example: '2024-10-25T10:00:00Z',
    })
    appointment_end: Date

    @IsNotEmpty()
    @ApiProperty({
        description: "ID du service lié à l'appointment",
        example: 1,
    })
    service_id: number

    @IsOptional()
    @ApiProperty({
        description: "ID du service mécanique lié à l'appointment",
        example: 2,
        required: false,
    })
    mechanical_service_id?: number

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Constructeur du véhicule',
        example: 'Toyota',
        required: false,
    })
    car_manufacturer?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Modèle du véhicule',
        example: 'Corolla',
        required: false,
    })
    car_model?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Année du véhicule',
        example: '2020',
        required: false,
    })
    car_year?: string

    @IsString()
    @IsOptional()
    @ApiProperty({
        description: 'Motorisation du véhicule',
        example: 'Hybride',
        required: false,
    })
    car_motorisation?: string
}
