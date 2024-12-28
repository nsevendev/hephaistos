import { Injectable } from '@nestjs/common'
import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Appointment } from '../domaine/appointment.entity'

@Injectable()
export class AppointmentRepository {
    constructor(
        @InjectRepository(Appointment)
        public readonly repository: Repository<Appointment>
    ) {}
}
