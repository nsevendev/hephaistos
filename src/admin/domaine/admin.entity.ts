import { Column, Entity, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
export class Admin {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "id de l'admin" })
    id: number

    @Column()
    @ApiProperty({ description: "Nom d'utilisateur de l'admin" })
    username: string

    @Column()
    @ApiProperty({ description: "Email de l'admin" })
    email: string

    @Column()
    @ApiProperty({ description: "Mot de passe de l'admin" })
    password: string

    @Column()
    @ApiProperty({ description: "Rôle de l'admin" })
    role: string

    @Column({ nullable: true }) // rend le token optionnel
    @ApiProperty({ description: "Token d'authentification de l'admin" })
    token: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: "Date de création du compte de l'admin" })
    created_at: Date
}
