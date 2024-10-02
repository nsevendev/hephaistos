import { InjectRepository } from '@nestjs/typeorm'
import { Repository } from 'typeorm'
import { Appointment } from '../domaine/appointment.entity'
import { Injectable } from '@nestjs/common'

@Injectable()
export class AppointmentRepository {
    constructor(
        @InjectRepository(Appointment)
        public repository: Repository<Appointment>
    ) {}
}
