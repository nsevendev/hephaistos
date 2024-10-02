import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Service } from '../../service/domaine/service.entity' // Assurez-vous que le chemin vers Service est correct
import { MechanicalService } from '../../mechanical-service/domaine/mechanical-service.entity' // Assurez-vous que le chemin vers MechanicalService est correct

@Entity()
export class Appointment {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du rendez-vous' })
    id: number

    @Column({ type: 'timestamp' })
    @ApiProperty({ description: 'Date et heure de début du rendez-vous', required: true })
    appointment_start: Date

    @Column({ type: 'timestamp' })
    @ApiProperty({ description: 'Date et heure de fin du rendez-vous', required: true })
    appointment_end: Date

    @ManyToOne(() => Service, { nullable: false })
    @JoinColumn({ name: 'service' }) // Colonne pour lier le service par ID
    @ApiProperty({ description: 'ID du service lié au rendez-vous' })
    service: Service // Lien vers la classe Service

    @ManyToOne(() => MechanicalService, { nullable: true })
    @JoinColumn({ name: 'mechanical_service' }) // Colonne pour lier le service mécanique par ID
    @ApiProperty({ description: 'ID du service mécanique lié au rendez-vous (optionnel)' })
    mechanical_service: MechanicalService // Lien vers la classe MechanicalService

    @Column({ nullable: true })
    @ApiProperty({ description: 'Fabricant de la voiture (optionnel)' })
    car_manufacturer: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Modèle de la voiture (optionnel)' })
    car_model: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Année de fabrication de la voiture (optionnel)' })
    car_year: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Motorisation de la voiture (optionnel)' })
    car_motorisation: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du rendez-vous' })
    created_at: Date
}
