import { Column, Entity, ManyToOne, OneToMany, PrimaryGeneratedColumn } from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { User } from '../../user/domaine/user.entity'
import { CarForSaleImage } from '../../car-for-sale-image/domaine/car-for-sale-image.entity'

@Entity()
export class CarForSale {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: 'ID de la voiture' })
    id: number

    @Column()
    @ApiProperty({ description: 'Fabricant de la voiture', required: true })
    manufacturer: string

    @Column()
    @ApiProperty({ description: 'Modèle de la voiture', required: true })
    model: string

    @Column('float')
    @ApiProperty({ description: 'Prix de la voiture', required: true })
    price: number

    @Column('float')
    @ApiProperty({ description: 'Puissance de la voiture en chevaux', required: true })
    power: number

    @Column('float')
    @ApiProperty({ description: 'Puissance fiscale de la voiture', required: true })
    tax_power: number

    @Column()
    @ApiProperty({ description: 'Type de carburant utilisé', required: true })
    fuel: string

    @Column()
    @ApiProperty({ description: 'Kilométrage de la voiture', required: true })
    mileage: number

    @Column()
    @ApiProperty({
        description: 'Type de transmission de la voiture',
        required: true,
    })
    conveyance_type: string

    @Column()
    @ApiProperty({ description: 'Couleur de la voiture', required: true })
    color: string

    @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
    @ApiProperty({ description: 'Date de création du véhicule à vendre', required: true })
    created_at: Date

    @ManyToOne(() => User, (user) => user.id, { nullable: true })
    @ApiProperty({ description: "Utilisateur qui a crée l'annonce", required: true })
    created_by?: User

    @OneToMany(() => CarForSaleImage, (carForSaleImage) => carForSaleImage.car_for_sale, { cascade: true })
    @ApiProperty({ description: 'Images associées à la voiture', type: () => [CarForSaleImage] })
    images?: CarForSaleImage[]
}
