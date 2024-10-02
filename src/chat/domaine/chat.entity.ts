import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { ChatRoom } from '../../chat-room/domaine/chat-room.entity' // Assurez-vous que le chemin est correct

@Entity()
export class Chat {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du message de chat' })
    id: number

    @Column()
    @ApiProperty({ description: 'Contenu du message de chat' })
    message: string

    @ManyToOne(() => ChatRoom, { nullable: false, onDelete: 'CASCADE' })
    @JoinColumn({ name: 'room_id' })
    @ApiProperty({ description: 'ID de la salle de discussion associée' })
    room_id: number

    @Column()
    @ApiProperty({ description: "Nom de l'expéditeur du message" })
    sender: string

    @Column({ default: false })
    @ApiProperty({ description: 'Statut de lecture du message' })
    readed: boolean

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du message' })
    created_at: Date
}
