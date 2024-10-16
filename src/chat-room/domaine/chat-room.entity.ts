import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, Unique } from 'typeorm'

@Entity()
@Unique(['access_code', 'email'])
export class ChatRoom {
    @PrimaryGeneratedColumn()
    id: number

    @Column({ length: 8, unique: true })
    access_code: string

    @Column()
    email: string

    @Column()
    firstname: string

    @Column()
    lastname: string

    @CreateDateColumn()
    created_at: Date
}
