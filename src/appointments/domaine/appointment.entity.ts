import { Column, Entity, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
export class Appointment {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'id du rendez-vous' })
    id: number

    @Column({ type: 'timestamp' })
    @ApiProperty({ description: 'Date et heure de début du rendez-vous' })
    appointment_start: Date

    @Column({ type: 'timestamp' })
    @ApiProperty({ description: 'Date et heure de fin du rendez-vous' })
    appointment_end: Date

    @Column()
    @ApiProperty({ description: 'Section ou domaine lié au rendez-vous' })
    section: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Service lié au rendez-vous (optionnel)' })
    service: string

    @Column()
    @ApiProperty({ description: 'Enregistrement ou référence liée au rendez-vous' })
    registration: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du rendez-vous' })
    created_at: Date
}
