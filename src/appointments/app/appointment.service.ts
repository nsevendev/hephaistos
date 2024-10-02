import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { AppointmentRepository } from '../infra/appointment.repository'

@Injectable()
export class AppointmentService extends BaseService {
    constructor(private readonly appointmentRepository: AppointmentRepository) {
        super('AppointmentService')
    }

    getAppointments = async () => {
        return await this.appointmentRepository.repository.find()
    }

    createAppointment = () => {
        return this.appointmentRepository.repository.create()
    }
}
