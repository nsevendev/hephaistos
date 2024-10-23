import { Module } from '@nestjs/common'
import { AppointmentService } from './app/appointment.service'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Appointment } from './domaine/appointment.entity'
// import { AppointmentController } from './app/appointment.controller'
import { AppointmentRepository } from './infra/appointment.repository'
import { ServiceModule } from '../service/service.module'
import { MechanicalServiceModule } from '../mechanical-service/mechanical-service.module'
import { UserModule } from '../user/user.module'
import { RoleModule } from '../role/role.module'

@Module({
    imports: [
        TypeOrmModule.forFeature([Appointment]),
        ServiceModule,
        MechanicalServiceModule,
        UserModule,
        RoleModule,
    ],
    providers: [AppointmentService, AppointmentRepository],
    exports: [AppointmentService, AppointmentRepository],
    // controllers: [AppointmentController],
})
export class AppointmentModule {}
