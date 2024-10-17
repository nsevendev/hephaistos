import { Column, Entity, PrimaryGeneratedColumn, CreateDateColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
export class Notification {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID de la notification' })
    id: number

    @Column()
    @ApiProperty({ description: 'Message de la notification', required: true })
    message: string

    @Column({ default: false })
    @ApiProperty({ description: 'Statut de lecture de la notification', required: true, default: false })
    readed: boolean

    @CreateDateColumn()
    @ApiProperty({ description: 'Date de création de la notification', type: 'string', format: 'date-time' })
    created_at: Date
}
