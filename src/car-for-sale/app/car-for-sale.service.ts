import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common'
import { CarForSaleRepository } from '../infra/car-for-sale.repository'
import { UserRepository } from '../../user/infra/user.repository'
import { CreateCarForSaleDto } from './create-car-for-sale.dto'
import { In } from 'typeorm'
import { UpdateCarForSaleDto } from './update-car-for-sale.dto'

@Injectable()
export class CarForSaleService {
    constructor(
        private readonly carForSaleRepository: CarForSaleRepository,
        private readonly userRepository: UserRepository
    ) {}

    getCarForSales = async (ids: number[] = []) => {
        if (!Array.isArray(ids) || !ids.every((id) => typeof id === 'number')) {
            throw new BadRequestException('Les IDs fournis doivent être un tableau de nombres.')
        }

        if (ids.length === 0) {
            return await this.carForSaleRepository.repository.find({ relations: ['created_by'] })
        }

        const carForSales = await this.carForSaleRepository.repository.find({
            where: { id: In(ids) },
            relations: ['created_by'],
        })

        const notFoundIds = ids.filter((id) => !carForSales.some((carForSale) => carForSale.id === id))
        if (notFoundIds.length > 0) {
            throw new NotFoundException(
                `Les véhicules avec les IDs suivants sont introuvables : ${notFoundIds.join(', ')}.`
            )
        }

        return carForSales
    }

    createCarForSale = async (createCarForSaleDto: CreateCarForSaleDto) => {
        const {
            manufacturer,
            model,
            price,
            power,
            tax_power,
            fuel,
            mileage,
            conveyance_type,
            color,
            created_by,
        } = createCarForSaleDto

        const user = await this.userRepository.repository.findOne({ where: { id: created_by } })
        if (!user) {
            throw new BadRequestException("L'utilisateur spécifié est introuvable.")
        }

        const newCarForSale = this.carForSaleRepository.repository.create({
            manufacturer,
            model,
            price,
            power,
            tax_power,
            fuel,
            mileage,
            conveyance_type,
            color,
            created_by: user,
            created_at: new Date(),
        })

        return await this.carForSaleRepository.repository.save(newCarForSale)
    }

    updateCarForSale = async (id: number, updateCarForSaleDto: UpdateCarForSaleDto) => {
        const carForSale = await this.carForSaleRepository.repository.findOne({
            where: { id },
            relations: ['created_by'],
        })

        if (!carForSale) {
            throw new NotFoundException(`Le véhicule avec l'ID ${id} est introuvable.`)
        }

        const { manufacturer, model, price, power, tax_power, fuel, mileage, conveyance_type, color } =
            updateCarForSaleDto

        if (manufacturer) carForSale.manufacturer = manufacturer
        if (model) carForSale.model = model
        if (price !== undefined) carForSale.price = price
        if (power !== undefined) carForSale.power = power
        if (tax_power !== undefined) carForSale.tax_power = tax_power
        if (fuel) carForSale.fuel = fuel
        if (mileage) carForSale.mileage = mileage
        if (conveyance_type) carForSale.conveyance_type = conveyance_type
        if (color) carForSale.color = color

        return await this.carForSaleRepository.repository.save(carForSale)
    }

    deleteCarForSale = async (id: number[]) => {
        const carForSale = await this.getCarForSales(id)
        await this.carForSaleRepository.repository.remove(carForSale)
    }
}
