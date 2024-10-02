import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Admin } from '../../admin/domaine/admin.entity' // Assurez-vous que le chemin vers Admin est correct

@Entity()
export class MechanicalService {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du service mécanique' })
    id: number

    @Column()
    @ApiProperty({ description: 'Nom du service mécanique', required: true })
    name: string

    @Column('decimal')
    @ApiProperty({ description: 'Prix minimal du service mécanique', required: true })
    lower_price: number

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du service' })
    created_at: Date

    // Relation ManyToOne avec Admin pour lier le créateur du service
    @ManyToOne(() => Admin, { nullable: false })
    @JoinColumn({ name: 'created_by' }) // Spécifie la colonne 'created_by' comme clé étrangère
    @ApiProperty({ description: 'Admin qui a créé le service' })
    created_by: Admin
}
