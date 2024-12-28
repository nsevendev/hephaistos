import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'

@Entity()
export class InterfaceImage {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "ID de l'image" })
    id: number

    @Column()
    @ApiProperty({ description: "URL de l'image sur AWS S3", required: true })
    url: string

    @Column()
    @ApiProperty({ description: "Clé de l'image dans AWS S3", required: true })
    aws_key: string

    @CreateDateColumn()
    @ApiProperty({ description: "Date de création de l'image" })
    created_at: Date
}
