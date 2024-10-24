import {
    Entity,
    PrimaryGeneratedColumn,
    Column,
    ManyToOne,
    CreateDateColumn,
    UpdateDateColumn,
} from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../user/domaine/user.entity'
import { CarForSale } from '../../car-for-sale/domaine/car-for-sale.entity'

@Entity()
export class CarForSaleImage {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "ID de l'image" })
    id: number

    @ManyToOne(() => CarForSale, (carForSale) => carForSale.id, { nullable: false, onDelete: 'CASCADE' })
    @ApiProperty({ description: "Voiture associée à l'image", required: true })
    car_for_sale: CarForSale

    @Column()
    @ApiProperty({ description: "URL de l'image sur AWS S3", required: true })
    url: string

    @Column()
    @ApiProperty({ description: "Clé de l'image dans AWS S3", required: true })
    aws_key: string

    @ManyToOne(() => User, { nullable: false })
    @ApiProperty({ description: "Utilisateur ayant créé l'image", required: true })
    created_by: User

    @CreateDateColumn()
    @ApiProperty({ description: "Date de création de l'image" })
    created_at: Date

    @UpdateDateColumn({ nullable: true })
    @ApiProperty({ description: "Date de mise à jour de l'image", required: false })
    updated_at?: Date
}
