import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { AppointmentService } from './app/appointment.service'
import { AppointmentRepository } from './infra/appointment.repository'
import { Appointment } from './domaine/appointment.entity'
import { CreateAppointmentDto } from './app/create-appointment.dto'
import { UpdateAppointmentDto } from './app/update-appointment.dto'
import { BadRequestException, NotFoundException } from '@nestjs/common'
import { ServiceService } from '../service/app/service.service'
import { MechanicalServiceService } from '../mechanical-service/app/mechanical-service.service'
import { ServiceModule } from '../service/service.module'
import { MechanicalServiceModule } from '../mechanical-service/mechanical-service.module'
import { UserService } from '../user/app/user.service'
import { UserModule } from '../user/user.module'
import { CreateUserDto } from '../user/app/create-user.dto'
import { RoleService } from '../role/app/role.service'
import { RoleModule } from '../role/role.module'
import { AppointmentController } from './app/appointment.controller'

describe('Appointment', () => {
    let appointmentService: AppointmentService
    let appointmentController: AppointmentController
    let appointmentRepository: AppointmentRepository
    let serviceService: ServiceService
    let mechanicalServiceService: MechanicalServiceService
    let userService: UserService
    let roleService: RoleService
    let module: TestingModule
    let userCreated: any

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [
                DatabaseTestModule,
                TypeOrmModule.forFeature([Appointment]),
                ServiceModule,
                MechanicalServiceModule,
                UserModule,
                RoleModule,
            ],
            providers: [AppointmentService, AppointmentRepository],
            controllers: [AppointmentController],
        }).compile()

        appointmentService = module.get<AppointmentService>(AppointmentService)
        appointmentController = module.get<AppointmentController>(AppointmentController)
        appointmentRepository = module.get<AppointmentRepository>(AppointmentRepository)
        serviceService = module.get<ServiceService>(ServiceService)
        mechanicalServiceService = module.get<MechanicalServiceService>(MechanicalServiceService)
        userService = module.get<UserService>(UserService)
        roleService = module.get<RoleService>(RoleService)
    })

    beforeEach(async () => {
        const role = await roleService.createRole({ name: 'admin' })

        const userData: CreateUserDto = {
            username: 'user1',
            email: 'user1@example.com',
            password: 'password123',
            role: role.id,
        }

        userCreated = await userService.createUser(userData)
    })

    describe('Service', () => {
        it('AppointmentService est défini', () => {
            expect(appointmentService).toBeDefined()
        })

        it('AppointmentRepository est défini', () => {
            expect(appointmentRepository).toBeDefined()
        })

        it('ServiceRepository est défini', () => {
            expect(serviceService).toBeDefined()
        })

        it('MechanicalServiceRepository est défini', () => {
            expect(mechanicalServiceService).toBeDefined()
        })

        it('AppointmentService.addAppointment devrait ajouter un rendez-vous avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const mechanicalService = await mechanicalServiceService.createMechanicalService({
                name: 'Mechanical Service Test',
                lower_price: 100,
                created_by: userCreated.id,
            })

            const createAppointmentDto: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: mechanicalService.id,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentService.addAppointment(createAppointmentDto)

            expect(appointment).toBeDefined()
            expect(appointment.service.id).toEqual(service.id)
            expect(appointment.mechanical_service.id).toEqual(mechanicalService.id)
        })

        it("AppointmentService.addAppointment devrait lancer une erreur si le service n'existe pas", async () => {
            const createAppointmentDto: CreateAppointmentDto = {
                service_id: 999,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            await expect(appointmentService.addAppointment(createAppointmentDto)).rejects.toThrow(
                BadRequestException
            )
        })

        it("AppointmentService.addAppointment devrait lancer une erreur si le service mécanique n'existe pas", async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })

            const createAppointmentDto: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: 999,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            await expect(appointmentService.addAppointment(createAppointmentDto)).rejects.toThrow(
                BadRequestException
            )
        })

        it('AppointmentService.getAppointment devrait récupérer tous les rendez-vous', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            await appointmentService.addAppointment(appointmentData)

            const appointments = await appointmentService.getAppointment()

            expect(appointments).toHaveLength(1)
        })

        it('AppointmentService.getAppointment devrait récupérer un rendez-vous par ID', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentService.addAppointment(appointmentData)
            const retrievedAppointment = await appointmentService.getAppointment([appointment.id])

            expect(retrievedAppointment).toHaveLength(1)
        })

        it('AppointmentService.updateAppointments devrait mettre à jour un rendez-vous avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const service2 = await serviceService.createService({
                name: 'Service Test2',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentService.addAppointment(appointmentData)
            const updateData: UpdateAppointmentDto = { service_id: service2.id }

            const updatedAppointments = await appointmentService.updateAppointments(
                [appointment.id],
                updateData
            )

            expect(updatedAppointments[0].service.id).toEqual(service2.id)
        })

        it("AppointmentService.updateAppointments devrait lancer une erreur si le rendez-vous n'existe pas", async () => {
            const updateData: UpdateAppointmentDto = { service_id: 2 }

            await expect(appointmentService.updateAppointments([999], updateData)).rejects.toThrow(
                NotFoundException
            )
        })

        it('AppointmentService.deleteAppointments devrait supprimer un rendez-vous avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentService.addAppointment(appointmentData)
            await appointmentService.deleteAppointments([appointment.id])

            const appointments = await appointmentService.getAppointment()

            expect(appointments).toHaveLength(0)
        })

        it("AppointmentService.deleteAppointments devrait lancer une erreur si aucun ID n'est fourni", async () => {
            await expect(appointmentService.deleteAppointments([])).rejects.toThrow(BadRequestException)
        })

        it("AppointmentService.deleteAppointments devrait lancer une erreur si le rendez-vous n'existe pas", async () => {
            await expect(appointmentService.deleteAppointments([999])).rejects.toThrow(NotFoundException)
        })
    })

    describe('Controller', () => {
        it('AppointmentController est défini', () => {
            expect(appointmentController).toBeDefined()
        })

        it('AppointmentController.createAppointment devrait créer un rendez-vous', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const mechanicalService = await mechanicalServiceService.createMechanicalService({
                name: 'Mechanical Service Test',
                lower_price: 100,
                created_by: userCreated.id,
            })

            const createAppointmentDto: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: mechanicalService.id,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentController.createAppointment(createAppointmentDto)

            expect(appointment).toBeDefined()
            expect(appointment.service.id).toEqual(service.id)
            expect(appointment.mechanical_service.id).toEqual(mechanicalService.id)
        })

        it('AppointmentController.getAllAppointments devrait récupérer tous les rendez-vous', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            await appointmentController.createAppointment(appointmentData)

            const appointments = await appointmentController.getAppointment('')

            expect(appointments).toHaveLength(1)
        })

        it('AppointmentController.getAppointmentById devrait récupérer un rendez-vous par ID', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentController.createAppointment(appointmentData)
            const retrievedAppointment = await appointmentController.getAppointment(`${appointment.id}`)

            expect(retrievedAppointment).toBeDefined()
            expect(retrievedAppointment[0].id).toEqual(appointment.id)
        })

        it('AppointmentController.updateAppointment devrait mettre à jour un rendez-vous avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const service2 = await serviceService.createService({
                name: 'Service Test2',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentController.createAppointment(appointmentData)
            const updateData: UpdateAppointmentDto = { service_id: service2.id }

            const updatedAppointment = await appointmentController.updateAppointments(
                `${appointment.id}`,
                updateData
            )

            expect(updatedAppointment[0].service.id).toEqual(service2.id)
        })

        it('AppointmentController.deleteAppointment devrait supprimer un rendez-vous avec succès', async () => {
            const service = await serviceService.createService({
                name: 'Service Test',
                created_by: userCreated.id,
            })
            const appointmentData: CreateAppointmentDto = {
                service_id: service.id,
                mechanical_service_id: null,
                appointment_start: new Date(),
                appointment_end: new Date(),
            }

            const appointment = await appointmentController.createAppointment(appointmentData)
            await appointmentController.deleteAppointments(`${appointment.id}`)

            const appointments = await appointmentController.getAppointment('')

            expect(appointments).toHaveLength(0)
        })
    })
})
