import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Appointment } from './domaine/appointment.entity'
import { AppointmentService } from './app/appointment.service'
import { AppointmentRepository } from './infra/appointment.repository'

describe('AppointmentModule', () => {
    let appointmentService: AppointmentService
    let appointmentRepository: AppointmentRepository
    let module: TestingModule

    beforeEach(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule, // Utilisation bdd pour les tests
                TypeOrmModule.forFeature([Appointment]),
            ],
            providers: [AppointmentService, AppointmentRepository],
        }).compile()

        appointmentService = module.get<AppointmentService>(AppointmentService)
        appointmentRepository = module.get<AppointmentRepository>(AppointmentRepository)
    })

    describe('Service', () => {
        it('AppointmentService est defini', () => {
            expect(appointmentService).toBeDefined()
        })

        it('AppointmentService.getAppointments avec aucun appointment', async () => {
            const appointments = await appointmentService.getAppointments()
            expect(appointments).toEqual([])
        })

        it('AppointmentService.getAppointments avec appointment', async () => {
            const appointmentCreated = await appointmentService.createAppointment()
            const appointments = await appointmentService.getAppointments()
            expect(appointments).toEqual([appointmentCreated])
        })
    })
})
