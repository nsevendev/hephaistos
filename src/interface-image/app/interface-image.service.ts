import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common'
import { InterfaceImageRepository } from '../infra/interface-image.repository'
import { CreateInterfaceImageDto } from '../app/create-interface-image.dto'
import { UpdateInterfaceImageDto } from '../app/update-interface-image.dto'
import { In } from 'typeorm'

@Injectable()
export class InterfaceImageService {
    constructor(private readonly interfaceImageRepository: InterfaceImageRepository) {}

    async addInterfaceImage(createInterfaceImageDto: CreateInterfaceImageDto) {
        const { url, aws_key } = createInterfaceImageDto

        const newImage = this.interfaceImageRepository.repository.create({
            url,
            aws_key,
            created_at: new Date(),
        })

        return await this.interfaceImageRepository.repository.save(newImage)
    }

    async updateInterfaceImage(id: number, updateInterfaceImageDto: UpdateInterfaceImageDto) {
        const existingImage = await this.interfaceImageRepository.repository.findOne({ where: { id } })
        if (!existingImage) {
            throw new NotFoundException(`L'image avec l'ID ${id} est introuvable.`)
        }

        Object.assign(existingImage, updateInterfaceImageDto)
        return await this.interfaceImageRepository.repository.save(existingImage)
    }

    async getInterfacesImages(ids: number[] = []) {
        if (!Array.isArray(ids) || !ids.every((id) => typeof id === 'number')) {
            throw new BadRequestException('Les IDs fournis doivent être un tableau de nombres.')
        }

        if (ids.length === 0) {
            const images = await this.interfaceImageRepository.repository.find()
            return { images, notFoundCount: 0 }
        }

        const images = await this.interfaceImageRepository.repository.find({
            where: { id: In(ids) },
        })

        const foundIds = images.map((image) => image.id)
        const notFoundCount = ids.length - foundIds.length

        return { images, notFoundCount }
    }

    async deleteInterfacesImages(ids: number[]) {
        const { images } = await this.getInterfacesImages(ids)

        if (images.length === 0) {
            throw new NotFoundException(`Aucune image trouvée avec les IDs donnés.`)
        }

        await this.interfaceImageRepository.repository.remove(images)

        return {
            deletedCount: images.length,
        }
    }
}
