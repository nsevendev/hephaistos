import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { InterfaceHomeRepository } from '../infra/interface-home.repository'
import { CreateInterfaceHomeDto } from './create-interface-home.dto'
import { UpdateInterfaceHomeDto } from './update-interface-home.dto'
import { InterfaceImageService } from '../../interface-image/app/interface-image.service'

@Injectable()
export class InterfaceHomeService {
    constructor(
        private readonly interfaceHomeRepository: InterfaceHomeRepository,
        private readonly interfaceImageService: InterfaceImageService
    ) {}

    addInterfaceHome = async (createInterfaceHomeDto: CreateInterfaceHomeDto) => {
        const { section1_image, section2_image, section3_image } = createInterfaceHomeDto

        const section1Result = await this.interfaceImageService.getInterfacesImages([section1_image])
        const section2Result = await this.interfaceImageService.getInterfacesImages([section2_image])
        const section3Result = await this.interfaceImageService.getInterfacesImages([section3_image])

        const section1Image = section1Result.images.length > 0 ? section1Result.images[0] : null
        const section2Image = section2Result.images.length > 0 ? section2Result.images[0] : null
        const section3Image = section3Result.images.length > 0 ? section3Result.images[0] : null
        console.log('ICIIIIIIIIIIII', section1_image)
        console.log('LAAAAAAAAAAAAAAAAAAAAAAAAAAAa', section1Result, section2Result, section3Result)

        if (!section1Image || !section2Image || !section3Image) {
            throw new BadRequestException('Les images spécifiées pour les sections sont introuvables.')
        }

        const newInterfaceHome = this.interfaceHomeRepository.repository.create({
            ...createInterfaceHomeDto,
            section1_image: section1Image,
            section2_image: section2Image,
            section3_image: section3Image,
        })

        return await this.interfaceHomeRepository.repository.save(newInterfaceHome)
    }

    getInterfaceHome = async () => {
        const interfaceHome = await this.interfaceHomeRepository.repository.findOne({})

        if (!interfaceHome) {
            throw new NotFoundException('Aucune interface home trouvée.')
        }

        return interfaceHome
    }

    updateInterfaceHome = async (id: number, updateInterfaceHomeDto: UpdateInterfaceHomeDto) => {
        const interfaceHome = await this.interfaceHomeRepository.repository.findOne({ where: { id } })

        if (!interfaceHome) {
            throw new NotFoundException(`Interface home avec l'ID ${id} non trouvée.`)
        }

        const updatedData = {
            ...interfaceHome,
            ...updateInterfaceHomeDto,
            section1_image: updateInterfaceHomeDto.section1_image
                ? (
                      await this.interfaceImageService.getInterfacesImages([
                          updateInterfaceHomeDto.section1_image,
                      ])
                  ).images[0] || interfaceHome.section1_image
                : interfaceHome.section1_image,
            section2_image: updateInterfaceHomeDto.section2_image
                ? (
                      await this.interfaceImageService.getInterfacesImages([
                          updateInterfaceHomeDto.section2_image,
                      ])
                  ).images[0] || interfaceHome.section2_image
                : interfaceHome.section2_image,
            section3_image: updateInterfaceHomeDto.section3_image
                ? (
                      await this.interfaceImageService.getInterfacesImages([
                          updateInterfaceHomeDto.section3_image,
                      ])
                  ).images[0] || interfaceHome.section3_image
                : interfaceHome.section3_image,
        }

        return await this.interfaceHomeRepository.repository.save(updatedData)
    }

    deleteInterfaceHome = async (id: number) => {
        const result = await this.interfaceHomeRepository.repository.delete(id)

        if (result.affected === 0) {
            throw new NotFoundException(`Interface home avec l'ID ${id} non trouvée.`)
        }

        return { message: `Interface home avec l'ID ${id} supprimée avec succès.` }
    }
}
