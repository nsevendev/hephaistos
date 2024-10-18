import { Column, Entity, JoinColumn, OneToMany, ManyToOne, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { Role } from '../../role/domaine/role.entity'
import { Service } from '../../service/domaine/service.entity'

@Entity()
export class User {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "ID de l'user" })
    id: number

    @Column()
    @ApiProperty({ description: "Nom d'utilisateur de l'user", required: true })
    username: string

    @Column()
    @ApiProperty({ description: "Email de l'user", required: true })
    email: string

    @Column()
    @ApiProperty({ description: 'Mot de passe', required: true })
    password: string

    @ManyToOne(() => Role, (role) => role.users, { eager: true })
    @JoinColumn({ name: 'role_id' })
    @ApiProperty({ description: "Rôle de l'utilisateur", type: () => Role })
    role: Role

    @OneToMany(() => Service, (service) => service.created_by, {
        cascade: true,
        nullable: true,
    })
    @ApiProperty({ description: "Services créés par l'utilisateur", type: () => [Service] })
    services?: Service[]
}
