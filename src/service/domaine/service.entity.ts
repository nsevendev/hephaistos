import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Admin } from '../../admin/domaine/admin.entity'

@Entity()
export class Service {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du service' })
    id: number

    @Column()
    @ApiProperty({ description: 'Nom du service', required: true })
    name: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du service' })
    created_at: Date

    @ManyToOne(() => Admin, { nullable: false })
    @JoinColumn({ name: 'created_by' })
    @ApiProperty({ description: "ID de l'admin qui a créé le service" })
    created_by: Admin
}
