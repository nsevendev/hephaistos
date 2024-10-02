import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Service } from '../../service/domaine/service.entity' // Assurez-vous que le chemin vers Service est correct
import { MechanicalService } from '../../mechanical-service/domaine/mechanical-service.entity' // Assurez-vous que le chemin vers MechanicalService est correct

@Entity()
export class Contact {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du contact' })
    id: number

    @Column()
    @ApiProperty({ description: 'Prénom du contact', required: true })
    firstname: string // Prénom, requis

    @Column()
    @ApiProperty({ description: 'Nom de famille du contact', required: true })
    lastname: string // Nom de famille, requis

    @Column()
    @ApiProperty({ description: 'Email du contact', required: true })
    email: string // Email, requis

    @Column()
    @ApiProperty({ description: 'Numéro de téléphone du contact', required: true })
    phone: string // Numéro de téléphone, requis

    @ManyToOne(() => Service, { nullable: false })
    @JoinColumn({ name: 'service' }) // Colonne pour lier le service par ID
    @ApiProperty({ description: 'ID du service lié au contact', required: true })
    service: Service // Lien vers la classe Service, requis

    @Column({ nullable: true })
    @ApiProperty({ description: 'Motorisation de la voiture (optionnel)' })
    car_motorisation: string // Motorisation, optionnelle

    @Column({ nullable: true })
    @ApiProperty({ description: 'Année de fabrication de la voiture (optionnel)' })
    car_year: string // Année de fabrication, optionnelle

    @Column({ nullable: true })
    @ApiProperty({ description: 'Modèle de la voiture (optionnel)' })
    car_model: string // Modèle de la voiture, optionnelle

    @Column({ nullable: true })
    @ApiProperty({ description: 'Fabricant de la voiture (optionnel)' })
    car_manufacturer: string // Fabricant de la voiture, optionnelle

    @ManyToOne(() => MechanicalService, { nullable: true })
    @JoinColumn({ name: 'mechanical_service' }) // Colonne pour lier le service mécanique par ID
    @ApiProperty({ description: 'ID du service mécanique lié au contact (optionnel)' })
    mechanical_service: MechanicalService // Lien vers la classe MechanicalService, optionnel

    @Column()
    @ApiProperty({ description: 'Message du contact', required: true })
    message: string // Message, requis

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du contact' })
    created_at: Date // Date de création
}
