import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Admin } from '../../admin/domaine/admin.entity' // Assurez-vous que le chemin vers Admin est correct

@Entity()
export class Service {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du service' })
    id: number

    @Column()
    @ApiProperty({ description: 'Nom du service', required: true })
    name: string // Propriété requise pour le nom du service

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du service' })
    created_at: Date // Date de création du service

    @ManyToOne(() => Admin, { nullable: false }) // Lien vers la table Admin
    @JoinColumn({ name: 'created_by' }) // Colonne pour lier le créateur par ID
    @ApiProperty({ description: "ID de l'admin qui a créé le service" })
    created_by: Admin // Propriété pour l'admin créateur
}
