import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { MechanicalServiceRepository } from '../infra/mechanical-service.repository'
import { CreateMechanicalServiceDto } from './create-mechanical-service.dto'
import { UpdateMechanicalServiceDto } from './update-mechanical-service.dto'
import { UserService } from '../../user/app/user.service'
import { In, Like } from 'typeorm'

@Injectable()
export class MechanicalServiceService {
    constructor(
        private readonly mechanicalServiceRepository: MechanicalServiceRepository,
        private readonly userService: UserService
    ) {}

    createMechanicalService = async (createMechanicalServiceDto: CreateMechanicalServiceDto) => {
        const { name, lower_price, created_by } = createMechanicalServiceDto

        const creator = await this.userService.getUsers([created_by])
        if (!creator[0]) {
            throw new BadRequestException("L'utilisateur spécifié est introuvable.")
        }

        const newService = this.mechanicalServiceRepository.repository.create({
            name,
            lower_price,
            created_by: creator[0],
        })

        return await this.mechanicalServiceRepository.repository.save(newService)
    }

    getMechanicalServices = async (mechanicalServiceIds: number[] = []) => {
        const mechanicalServices =
            mechanicalServiceIds && mechanicalServiceIds.length > 0
                ? await this.mechanicalServiceRepository.repository.find({
                      where: { id: In(mechanicalServiceIds) },
                      relations: ['created_by'],
                  })
                : await this.mechanicalServiceRepository.repository.find({ relations: ['created_by'] })

        if (mechanicalServiceIds && mechanicalServiceIds.length > 0 && mechanicalServices.length === 0) {
            throw new NotFoundException('Aucun service mécanique trouvé avec les IDs fournis.')
        }

        return mechanicalServices
    }

    getMechanicalServicesByFilter = async (nameToSearch: string) => {
        return await this.mechanicalServiceRepository.repository.find({
            where: { name: Like(`%${nameToSearch}%`) },
            relations: ['created_by'],
        })
    }

    updateMechanicalService = async (id: number, updatedData: UpdateMechanicalServiceDto) => {
        const existingService = await this.mechanicalServiceRepository.repository.findOne({
            where: { id },
            relations: ['created_by'],
        })

        if (!existingService) {
            throw new NotFoundException(`Le service mécanique avec l'ID ${id} n'existe pas.`)
        }

        const updatedService = {
            ...existingService,
            ...updatedData,
            created_by: existingService.created_by,
        }

        return await this.mechanicalServiceRepository.repository.save(updatedService)
    }

    deleteMechanicalServices = async (mechanicalServiceIds: number[]) => {
        const result = await this.mechanicalServiceRepository.repository.delete(mechanicalServiceIds)

        if (result.affected === 0) {
            throw new NotFoundException(`Aucun service mécanique trouvé pour les ID fournis.`)
        }

        return result
    }
}
