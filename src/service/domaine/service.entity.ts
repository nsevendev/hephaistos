import { Column, Entity, JoinColumn, ManyToOne, OneToMany, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../user/domaine/user.entity'
import { Contact } from '../../contact/domaine/contact.entity'
import { Appointment } from '../../appointment/domaine/appointment.entity'

@Entity()
export class Service {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du service' })
    id: number

    @Column()
    @ApiProperty({ description: 'Nom du service', required: true })
    name: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du service', required: true })
    created_at: Date

    @ManyToOne(() => User, (user) => user.services, { eager: true })
    @JoinColumn({ name: 'created_by' })
    @ApiProperty({ description: 'Utilisateur ayant créé le service', type: () => User })
    created_by: User

    @OneToMany(() => Contact, (contact) => contact.service, { nullable: true })
    @ApiProperty({ description: 'Contacts liés à ce service', type: () => [Contact], required: false })
    contacts: Contact[]

    @OneToMany(() => Appointment, (appointment) => appointment.service, { nullable: true })
    @ApiProperty({
        description: 'Appointments liés à ce service',
        type: () => [Appointment],
        required: false,
    })
    appointments: Appointment[]
}
