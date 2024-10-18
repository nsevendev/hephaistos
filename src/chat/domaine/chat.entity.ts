import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, ManyToOne } from 'typeorm'
import { ChatRoom } from '../../chat-room/domaine/chat-room.entity'

export enum SenderType {
    GARAGE = 'garage',
    CLIENT = 'client',
}

@Entity()
export class Chat {
    @PrimaryGeneratedColumn()
    id: number

    @Column({ type: 'text' })
    message: string

    @ManyToOne(() => ChatRoom, (room) => room.chats)
    room: ChatRoom

    @Column({
        type: 'enum',
        enum: SenderType,
        nullable: false,
    })
    sender: SenderType

    @Column({ type: 'boolean', default: false })
    readed: boolean

    @CreateDateColumn()
    created_at: Date
}
