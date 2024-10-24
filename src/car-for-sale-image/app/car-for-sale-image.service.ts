import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common'
import { CarForSaleImageRepository } from '../infra/car-for-sale-image.repository'
import { CarForSaleRepository } from '../../car-for-sale/infra/car-for-sale.repository'
import { UserRepository } from '../../user/infra/user.repository'
import { In } from 'typeorm'
import { CreateCarForSaleImageDto } from '../app/create-car-for-sale-image.dto'

@Injectable()
export class CarForSaleImageService {
    constructor(
        private readonly carForSaleImageRepository: CarForSaleImageRepository,
        private readonly carForSaleRepository: CarForSaleRepository,
        private readonly userRepository: UserRepository
    ) {}

    async addImages(createCarForSaleImageDto: CreateCarForSaleImageDto[]) {
        const imagesToAdd = []
        for (const imageDto of createCarForSaleImageDto) {
            const { car_for_sale, url, aws_key, created_by } = imageDto

            const car = await this.carForSaleRepository.repository.findOne({ where: { id: car_for_sale } })
            if (!car) {
                throw new BadRequestException(`La voiture avec l'ID ${car_for_sale} est introuvable.`)
            }

            const user = await this.userRepository.repository.findOne({ where: { id: created_by } })
            if (!user) {
                throw new BadRequestException(`L'utilisateur avec l'ID ${created_by} est introuvable.`)
            }

            const newImage = this.carForSaleImageRepository.repository.create({
                car_for_sale: car,
                url,
                aws_key,
                created_by: user,
                created_at: new Date(),
            })
            imagesToAdd.push(newImage)
        }

        return await this.carForSaleImageRepository.repository.save(imagesToAdd)
    }

    async getImages(ids: number[] = []) {
        if (!Array.isArray(ids) || !ids.every((id) => typeof id === 'number')) {
            throw new BadRequestException('Les IDs fournis doivent être un tableau de nombres.')
        }

        if (ids.length === 0) {
            return await this.carForSaleImageRepository.repository.find({
                relations: ['car_for_sale', 'created_by'],
            })
        }

        const images = await this.carForSaleImageRepository.repository.find({
            where: { id: In(ids) },
            relations: ['car_for_sale', 'created_by'],
        })

        const notFoundIds = ids.filter((id) => !images.some((image) => image.id === id))
        if (notFoundIds.length > 0) {
            throw new NotFoundException(
                `Les images avec les IDs suivants sont introuvables : ${notFoundIds.join(', ')}.`
            )
        }

        return images
    }

    async getImagesByCarForSale(carForSaleId: number) {
        const carForSale = await this.carForSaleRepository.repository.findOne({ where: { id: carForSaleId } })
        if (!carForSale) {
            throw new NotFoundException(`La voiture avec l'ID ${carForSaleId} est introuvable.`)
        }

        return await this.carForSaleImageRepository.repository.find({
            where: { car_for_sale: carForSale },
            relations: ['car_for_sale', 'created_by'],
        })
    }

    async deleteImages(ids: number[]) {
        const images = await this.getImages(ids)
        if (!images.length) {
            throw new NotFoundException(`Aucune image trouvée avec les IDs donnés.`)
        }

        await this.carForSaleImageRepository.repository.remove(images)
    }
}
