import { Column, Entity, OneToMany, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../admin/domaine/user.entity'

@Entity()
export class Role {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID du rôle' })
    id: number

    @Column()
    @ApiProperty({ description: 'Nom du rôle', required: true })
    name: string

    @OneToMany(() => User, (user) => user.role)
    users: User[]
}
