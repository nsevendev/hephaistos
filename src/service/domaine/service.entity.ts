import { Column, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../user/domaine/user.entity'

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
    @ApiProperty({ description: 'Utilisateur ayant créé le service' })
    @ApiProperty({ type: () => User })
    created_by: User
}
