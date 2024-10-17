import { Column, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn, CreateDateColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../user/domaine/user.entity'
import { Contact } from '../../contact/domaine/contact.entity'

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

    @ManyToOne(() => User, (user) => user.id, { eager: true })
    @JoinColumn({ name: 'created_by' })
    @ApiProperty({ description: 'Utilisateur ayant créé le service mécanique', required: true })
    created_by: User

    @ManyToOne(() => Contact, (contact) => contact.mechanical_service, { nullable: true })
    @JoinColumn({ name: 'contact_id' })
    @ApiProperty({ description: 'Contact lié au service mécanique', required: false, type: () => Contact })
    contact: Contact
}
