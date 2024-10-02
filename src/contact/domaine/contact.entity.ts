import { Column, Entity, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
export class Contact {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'id du contact' })
    id: number

    @Column()
    @ApiProperty({ description: 'Prénom du contact' })
    firstname: string

    @Column()
    @ApiProperty({ description: 'Nom de famille du contact' })
    lastname: string

    @Column()
    @ApiProperty({ description: 'Adresse email du contact' })
    email: string

    @Column()
    @ApiProperty({ description: 'Numéro de téléphone du contact' })
    phone: string

    @Column({ nullable: true })
    @ApiProperty({ description: 'Contexte du contact (reprogrammation,service mechanique...)(optionnel)' })
    context: string

    @Column({ nullable: true })
    @ApiProperty({ description: "Plaque d'mmatriculation du contact (optionnel)" })
    registration: string

    @Column()
    @ApiProperty({ description: 'Message du contact' })
    message: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du contact' })
    created_at: Date
}
