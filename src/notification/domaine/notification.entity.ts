import { Column, Entity, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
export class Notification {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID unique de la notification' })
    id: number

    @Column({ type: 'varchar', length: 255 })
    @ApiProperty({ description: 'Contexte de la notification', required: true })
    context: string

    @Column({ type: 'text' })
    @ApiProperty({ description: 'Message de la notification', required: true })
    message: string

    @Column({ type: 'boolean', default: false })
    @ApiProperty({ description: 'Statut de lecture de la notification', required: true, default: false })
    readed: boolean

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création de la notification', required: true })
    created_at: Date
}
