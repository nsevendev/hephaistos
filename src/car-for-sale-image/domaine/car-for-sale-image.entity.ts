import { Column, Entity, PrimaryGeneratedColumn, ManyToOne, JoinColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { CarForSale } from '../../car-for-sale/domaine/car-for-sale.entity' // Assurez-vous que le chemin est correct

@Entity()
export class CarForSaleImage {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "ID de l'image" })
    id: number

    @ManyToOne(() => CarForSale, { nullable: false, onDelete: 'CASCADE' })
    @JoinColumn({ name: 'car_for_sale' })
    @ApiProperty({ description: 'ID de la voiture associée' })
    car_for_sale: number

    @Column({ nullable: true })
    @ApiProperty({ description: "Description de l'image" })
    description: string

    @Column()
    @ApiProperty({ description: "URL de l'image" })
    image_url: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP', onUpdate: 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de dernière mise à jour' })
    updated_at: Date

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création' })
    created_at: Date
}
