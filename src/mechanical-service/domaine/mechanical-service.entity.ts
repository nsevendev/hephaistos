import {
    Column,
    Entity,
    JoinColumn,
    ManyToOne,
    OneToMany,
    PrimaryGeneratedColumn,
    CreateDateColumn,
} from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../user/domaine/user.entity'
import { Contact } from '../../contact/domaine/contact.entity'
import { Appointment } from '../../appointment/domaine/appointment.entity'

@Entity()
export class MechanicalService {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du service mécanique' })
    id: number

    @Column()
    @ApiProperty({ description: 'Nom du service mécanique', required: true })
    name: string

    @Column('float')
    @ApiProperty({ description: 'Prix minimum du service mécanique', required: true })
    lower_price: number

    @CreateDateColumn({ type: 'timestamp' })
    @ApiProperty({ description: 'Date de création du service mécanique', required: false })
    created_at: Date

    @ManyToOne(() => User, (user) => user.services, { eager: true })
    @JoinColumn({ name: 'created_by' })
    @ApiProperty({ description: 'Utilisateur ayant créé le service mécanique', type: () => User })
    created_by: User

    @OneToMany(() => Contact, (contact) => contact.mechanical_service, { nullable: true })
    @ApiProperty({
        description: 'Contacts liés au service mécanique',
        required: false,
        type: () => [Contact],
    })
    contacts: Contact[]

    @OneToMany(() => Appointment, (appointment) => appointment.mechanical_service, { nullable: true })
    @ApiProperty({
        description: 'Appointments liés au service mécanique',
        required: false,
        type: () => [Appointment],
    })
    appointments: Appointment[]
}
