import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { AppointmentRepository } from '../infra/appointment.repository'
import { CreateAppointmentDto } from './create-appointment.dto'
import { UpdateAppointmentDto } from './update-appointment.dto'
import { ServiceRepository } from '../../service/infra/service.repository'
import { MechanicalServiceRepository } from '../../mechanical-service/infra/mechanical-service.repository'
import { In, Between } from 'typeorm'

@Injectable()
export class AppointmentService {
    constructor(
        private readonly appointmentRepository: AppointmentRepository,
        private readonly serviceRepository: ServiceRepository,
        private readonly mechanicalServiceRepository: MechanicalServiceRepository
    ) {}

    async addAppointment(createAppointmentDto: CreateAppointmentDto) {
        const { service_id, mechanical_service_id, ...rest } = createAppointmentDto

        const service = await this.serviceRepository.repository.findOne({ where: { id: service_id } })
        if (!service) {
            throw new BadRequestException(`Le service avec l'ID ${service_id} n'existe pas.`)
        }

        let mechanicalService = null
        if (mechanical_service_id) {
            mechanicalService = await this.mechanicalServiceRepository.repository.findOne({
                where: { id: mechanical_service_id },
            })
            if (!mechanicalService) {
                throw new BadRequestException(
                    `Le service mécanique avec l'ID ${mechanical_service_id} n'existe pas.`
                )
            }
        }

        const newAppointment = this.appointmentRepository.repository.create({
            ...rest,
            service,
            mechanical_service: mechanicalService,
        })

        return await this.appointmentRepository.repository.save(newAppointment)
    }

    async getAppointment(ids: number[] = []) {
        if (ids.length > 0) {
            return await this.appointmentRepository.repository.find({
                where: { id: In(ids) },
                relations: ['service', 'mechanical_service'],
            })
        } else {
            return await this.appointmentRepository.repository.find({
                relations: ['service', 'mechanical_service'],
            })
        }
    }

    async getAppointmentsByDate(startDate: Date, endDate: Date) {
        return await this.appointmentRepository.repository.find({
            where: {
                appointment_start: Between(startDate, endDate),
                appointment_end: Between(startDate, endDate),
            },
            relations: ['service', 'mechanical_service'],
        })
    }

    async updateAppointments(ids: number[], updateAppointmentDto: UpdateAppointmentDto) {
        const appointmentsToUpdate = await this.appointmentRepository.repository.find({
            where: { id: In(ids) },
            relations: ['service', 'mechanical_service'],
        })

        if (appointmentsToUpdate.length === 0) {
            throw new NotFoundException(`Aucun appointment correspondant aux IDs fournis.`)
        }

        for (const appointment of appointmentsToUpdate) {
            Object.assign(appointment, updateAppointmentDto)

            if (updateAppointmentDto.service_id) {
                const service = await this.serviceRepository.repository.findOne({
                    where: { id: updateAppointmentDto.service_id },
                })
                if (!service) {
                    throw new BadRequestException(
                        `Le service avec l'ID ${updateAppointmentDto.service_id} n'existe pas.`
                    )
                }
                appointment.service = service
            }

            if (updateAppointmentDto.mechanical_service_id) {
                const mechanicalService = await this.mechanicalServiceRepository.repository.findOne({
                    where: { id: updateAppointmentDto.mechanical_service_id },
                })
                if (!mechanicalService) {
                    throw new BadRequestException(
                        `Le service mécanique avec l'ID ${updateAppointmentDto.mechanical_service_id} n'existe pas.`
                    )
                }
                appointment.mechanical_service = mechanicalService
            }

            await this.appointmentRepository.repository.save(appointment)
        }

        return appointmentsToUpdate
    }

    async deleteAppointments(ids: number[]) {
        if (ids.length === 0) {
            throw new BadRequestException(`Aucun ID fourni pour la suppression.`)
        }

        const appointmentsToDelete = await this.appointmentRepository.repository.find({
            where: { id: In(ids) },
        })
        if (appointmentsToDelete.length === 0) {
            throw new NotFoundException(`Aucun appointment correspondant aux IDs fournis.`)
        }

        const deletedAppointments = await this.appointmentRepository.repository.remove(appointmentsToDelete)
        return { deletedCount: deletedAppointments.length }
    }
}
