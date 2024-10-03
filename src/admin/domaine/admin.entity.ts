import { Column, Entity, PrimaryGeneratedColumn, ManyToOne } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Role } from '../../role/domaine/role.entity'

@Entity()
export class Admin {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "id de l'admin" })
    id: number

    @Column()
    @ApiProperty({ description: "Nom d'utilisateur de l'admin", required: true })
    username: string

    @Column()
    @ApiProperty({ description: "Email de l'admin", required: true })
    email: string

    @Column()
    @ApiProperty({ description: "Mot de passe de l'admin", required: true })
    password: string

    @ManyToOne(() => Role, { nullable: false })
    @ApiProperty({ description: "Rôle de l'admin", required: true })
    role: Role

    @Column({ nullable: true })
    @ApiProperty({ description: "Token d'authentification de l'admin" })
    token: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: "Date de création du compte de l'admin" })
    created_at: Date
}
