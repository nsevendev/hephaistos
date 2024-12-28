import { Column, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn, CreateDateColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Service } from '../../service/domaine/service.entity'
import { MechanicalService } from '../../mechanical-service/domaine/mechanical-service.entity'

@Entity()
export class Appointment {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "ID de l'appointment" })
    id: number

    @Column()
    @ApiProperty({ description: "Date de début de l'appointment", required: true })
    appointment_start: Date

    @Column()
    @ApiProperty({ description: "Date de fin de l'appointment", required: true })
    appointment_end: Date

    @ManyToOne(() => Service, (service) => service.appointments, { eager: true })
    @JoinColumn({ name: 'service_id' })
    @ApiProperty({ description: "Service lié à l'appointment", type: () => Service, required: true })
    service: Service

    @ManyToOne(() => MechanicalService, (mechanicalService) => mechanicalService.appointments, {
        eager: true,
        nullable: true,
    })
    @JoinColumn({ name: 'mechanical_service_id' })
    @ApiProperty({
        description: "Service mécanique lié à l'appointment",
        type: () => MechanicalService,
        required: false,
    })
    mechanical_service: MechanicalService

    @Column({ nullable: true })
    @ApiProperty({ description: 'Constructeur du véhicule', required: false })
    car_manufacturer: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Modèle du véhicule', required: false })
    car_model: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Année du véhicule', required: false })
    car_year: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Motorisation du véhicule', required: false })
    car_motorisation: string

    @CreateDateColumn({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: "Date de création de l'appointment", required: true })
    created_at: Date
}
