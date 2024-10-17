import { Column, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn } from 'typeorm'
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
    @ApiProperty({ description: 'Nom du contact', required: true })
    lastname: string

    @Column({ unique: true })
    @ApiProperty({ description: 'Email du contact', required: true })
    email: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Numéro de téléphone du contact', required: false })
    phone: string

    @ManyToOne(() => Service, (service) => service.contacts, { eager: true })
    @JoinColumn({ name: 'service_id' })
    @ApiProperty({ description: 'Service lié au contact', type: () => Service, required: true })
    service: Service

    @Column({ nullable: true })
    @ApiProperty({ description: 'Motorisation du véhicule', required: false })
    car_motorisation: string

    @Column({ type: 'int', nullable: true })
    @ApiProperty({ description: 'Année du véhicule', required: false })
    car_year: number

    @Column()
    @ApiProperty({ description: 'Modèle du véhicule', required: true })
    car_model: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Constructeur du véhicule', required: false })
    car_manufacturer: string

    @ManyToOne(() => MechanicalService, (mechanicalService) => mechanicalService.contacts, {
        eager: true,
        nullable: true,
    })
    @JoinColumn({ name: 'mechanical_service_id' })
    @ApiProperty({
        description: 'Service mécanique lié au contact',
        type: () => MechanicalService,
        required: false,
    })
    mechanical_service: MechanicalService

    @Column()
    @ApiProperty({ description: 'Message du contact', required: true })
    message: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du contact', required: true })
    created_at: Date
}
