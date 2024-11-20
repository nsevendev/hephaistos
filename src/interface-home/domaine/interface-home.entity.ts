import {
    Entity,
    PrimaryGeneratedColumn,
    Column,
    ManyToOne,
    CreateDateColumn,
    UpdateDateColumn,
    JoinColumn,
    Unique,
} from 'typeorm'
import { ApiProperty } from '@nestjs/swagger'
import { InterfaceImage } from '../../interface-image/domaine/interface-image.entity'

@Entity()
@Unique(['id'])
export class InterfaceHome {
    @PrimaryGeneratedColumn()
    @ApiProperty({ description: "ID de l'interface" })
    id: number

    @Column()
    @ApiProperty({ description: 'Titre de la page' })
    page_title: string

    @Column()
    @ApiProperty({ description: 'Texte de la page' })
    page_text: string

    @Column()
    @ApiProperty({ description: 'Titre de la section 1' })
    section1_title: string

    @ManyToOne(() => InterfaceImage, (interfaceImage) => interfaceImage.id, { nullable: false })
    @JoinColumn({ name: 'section1_image' })
    @ApiProperty({ description: 'Image associée à la section 1', type: () => InterfaceImage })
    section1_image: InterfaceImage

    @Column()
    @ApiProperty({ description: 'Texte du bouton de la section 1' })
    section1_button_text: string

    @Column()
    @ApiProperty({ description: 'Titre de la section 2' })
    section2_title: string

    @ManyToOne(() => InterfaceImage, (interfaceImage) => interfaceImage.id, { nullable: false })
    @JoinColumn({ name: 'section2_image' })
    @ApiProperty({ description: 'Image associée à la section 2', type: () => InterfaceImage })
    section2_image: InterfaceImage

    @Column()
    @ApiProperty({ description: 'Texte du bouton de la section 2' })
    section2_button_text: string

    @Column()
    @ApiProperty({ description: 'Titre de la section 3' })
    section3_title: string

    @ManyToOne(() => InterfaceImage, (interfaceImage) => interfaceImage.id, { nullable: false })
    @JoinColumn({ name: 'section3_image' })
    @ApiProperty({ description: 'Image associée à la section 3', type: () => InterfaceImage })
    section3_image: InterfaceImage

    @Column()
    @ApiProperty({ description: 'Texte du bouton de la section 3' })
    section3_button_text: string

    @CreateDateColumn()
    @ApiProperty({ description: "Date de création de l'interface" })
    created_at: Date

    @UpdateDateColumn({ nullable: true })
    @ApiProperty({ description: "Date de mise à jour de l'interface", required: false })
    updated_at?: Date
}
