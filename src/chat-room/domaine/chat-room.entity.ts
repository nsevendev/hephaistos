import { Column, Entity, PrimaryGeneratedColumn, Unique, BeforeInsert } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
@Unique(['access_code']) // vérifie que access_code est bien unique
export class ChatRoom {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID de la salle de discussion' })
    id: number

    @Column()
    @ApiProperty({ description: "Code d'accès unique pour la salle de discussion" })
    access_code: string

    @Column()
    @ApiProperty({ description: 'Email associé à la salle de discussion' })
    email: string

    @Column()
    @ApiProperty({ description: "Prénom de l'utilisateur associé à la salle de discussion" })
    firstname: string

    @Column()
    @ApiProperty({ description: "Nom de famille de l'utilisateur associé à la salle de discussion" })
    lastname: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création de la salle de discussion' })
    created_at: Date

    // génération de l'access code
    @BeforeInsert()
    generateAccessCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' // Ensemble des caractères possibles
        this.access_code = Array.from({ length: 6 }, () =>
            chars.charAt(Math.floor(Math.random() * chars.length))
        ).join('')
    }
}
