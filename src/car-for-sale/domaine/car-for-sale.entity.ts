import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Admin } from '../../admin/domaine/admin.entity' // Assurez-vous que le chemin est correct

@Entity()
export class CarForSale {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'id de la voiture' })
    id: number

    @Column()
    @ApiProperty({ description: 'Constructeur de la voiture', required: true })
    manufacturer: string

    @Column()
    @ApiProperty({ description: 'Modèle de la voiture', required: true })
    model: string

    @Column('decimal')
    @ApiProperty({ description: 'Prix de la voiture', required: true })
    price: number

    @Column('decimal')
    @ApiProperty({ description: 'Puissance de la voiture (en chevaux)', required: true })
    power: number

    @Column('decimal')
    @ApiProperty({ description: 'Puissance fiscale de la voiture', required: true })
    tax_power: number

    @Column()
    @ApiProperty({ description: 'Type de carburant utilisé par la voiture', required: true })
    fuel: string

    @Column('decimal')
    @ApiProperty({ description: 'Kilométrage de la voiture', required: true })
    mileage: number

    @Column()
    @ApiProperty({ description: 'Type de transmission de la voiture', required: true })
    conveyance_type: string

    @Column()
    @ApiProperty({ description: 'Couleur de la voiture', required: true })
    color: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: "Date de création de l'annonce" })
    created_at: Date

    // Relation ManyToOne avec Admin
    @ManyToOne(() => Admin, { nullable: false })
    @JoinColumn({ name: 'created_by' }) // Définit la colonne created_by comme clé étrangère
    @ApiProperty({ description: "Admin qui a créé l'annonce" })
    created_by: Admin
}
