import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, Unique, Index } from 'typeorm'

@Entity()
@Unique(['access_code', 'email'])
export class ChatRoom {
    @PrimaryGeneratedColumn()
    id: number

    @Column({ type: 'varchar', length: 36, unique: true })
    @Index()
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
