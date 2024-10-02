import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Service } from '../../service/domaine/service.entity'
import { MechanicalService } from '../../mechanical-service/domaine/mechanical-service.entity'

@Entity()
export class Contact {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du contact' })
    id: number

    @Column()
    @ApiProperty({ description: 'Prénom du contact', required: true })
    firstname: string

    @Column()
    @ApiProperty({ description: 'Nom de famille du contact', required: true })
    lastname: string

    @Column()
    @ApiProperty({ description: 'Email du contact', required: true })
    email: string

    @Column()
    @ApiProperty({ description: 'Numéro de téléphone du contact', required: true })
    phone: string

    @ManyToOne(() => Service, { nullable: false })
    @JoinColumn({ name: 'service' })
    @ApiProperty({ description: 'ID du service lié au contact', required: true })
    service: Service

    @Column({ nullable: true })
    @ApiProperty({ description: 'Motorisation de la voiture (optionnel)' })
    car_motorisation: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Année de fabrication de la voiture (optionnel)' })
    car_year: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Modèle de la voiture (optionnel)' })
    car_model: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Fabricant de la voiture (optionnel)' })
    car_manufacturer: string

    @ManyToOne(() => MechanicalService, { nullable: true })
    @JoinColumn({ name: 'mechanical_service' })
    @ApiProperty({ description: 'ID du service mécanique lié au contact (optionnel)' })
    mechanical_service: MechanicalService

    @Column()
    @ApiProperty({ description: 'Message du contact', required: true })
    message: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du contact' })
    created_at: Date
}
