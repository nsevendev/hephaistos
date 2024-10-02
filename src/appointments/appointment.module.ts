import { Module } from '@nestjs/common'
import { AppointmentService } from './app/appointment.service'
import { AppointmentRepository } from './infra/appointment.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Appointment } from './domaine/appointment.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Appointment])],
    providers: [AppointmentService, AppointmentRepository],
    exports: [AppointmentService, AppointmentRepository],
})
export class AppointmentModule {}
