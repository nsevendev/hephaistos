import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, Unique } from 'typeorm'

@Entity()
@Unique(['access_code'])
export class ChatRoom {
    @PrimaryGeneratedColumn()
    id: number

    @Column({ length: 8 })
    access_code: string

    @Column()
    email: string

    @Column()
    firstname: string

    @Column()
    lastname: string

    @CreateDateColumn()
    created_at: Date

    static generateAccessCode(): string {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
        let accessCode = ''
        for (let i = 0; i < 8; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length)
            accessCode += characters.charAt(randomIndex)
        }
        return accessCode
    }
}
