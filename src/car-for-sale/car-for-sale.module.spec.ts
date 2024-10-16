import { Test, TestingModule } from '@nestjs/testing'
import { DatabaseTestModule } from '../database-test/database-test.module'
import { TypeOrmModule } from '@nestjs/typeorm'
import { User } from '../user/domaine/user.entity'
import { UserService } from '../user/app/user.service'
import { RoleService } from '../role/app/role.service'
import { CarForSaleService } from './app/car-for-sale.service'
import { CarForSaleRepository } from './infra/car-for-sale.repository'
import { CreateCarForSaleDto } from './app/create-car-for-sale.dto'
import { CarForSale } from './domaine/car-for-sale.entity'
import { UserModule } from '../user/user.module'
import { RoleModule } from '../role/role.module'
import { UpdateCarForSaleDto } from './app/update-car-for-sale.dto'
import { BadRequestException, NotFoundException } from '@nestjs/common'
import { CarForSaleController } from './app/car-for-sale.controller'

describe('CarForSaleService', () => {
    let carForSaleService: CarForSaleService
    let carForSaleController: CarForSaleController
    let userService: UserService
    let roleService: RoleService
    let module: TestingModule
    let userCreated: User

    beforeAll(async () => {
        module = await Test.createTestingModule({
            imports: [DatabaseTestModule, TypeOrmModule.forFeature([CarForSale]), UserModule, RoleModule],
            providers: [CarForSaleService, CarForSaleRepository, UserService, RoleService],
            controllers: [CarForSaleController],
        }).compile()

        carForSaleService = module.get<CarForSaleService>(CarForSaleService)
        carForSaleController = module.get<CarForSaleController>(CarForSaleController)
        userService = module.get<UserService>(UserService)
        roleService = module.get<RoleService>(RoleService)
    })

    beforeEach(async () => {
        const role = await roleService.createRole({ name: 'admin' })
        const userData = {
            username: 'user1',
            email: 'user1@example.com',
            password: 'password123',
            role: role.id,
        }
        userCreated = await userService.createUser(userData)
    })

    describe('Service', () => {
        it('CarForSaleService est défini', () => {
            expect(carForSaleService).toBeDefined()
        })

        it('createCarForSale crée un véhicule avec succès', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }

            const car = await carForSaleService.createCarForSale(createCarDto)
            expect(car).toBeDefined()
            expect(car.manufacturer).toEqual(createCarDto.manufacturer)
            expect(car.created_by.id).toEqual(userCreated.id)
        })

        it('getCarForSales retourne tous les véhicules si aucun ID n’est donné', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }
            const car = await carForSaleService.createCarForSale(createCarDto)
            const cars = await carForSaleService.getCarForSales([])
            expect(cars[0]).toMatchObject(car)
        })

        it('getCarForSales retourne un véhicule avec un ID valide', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }
            const car = await carForSaleService.createCarForSale(createCarDto)

            const cars = await carForSaleService.getCarForSales([car.id])
            expect(cars[0]).toMatchObject(car)
        })

        it('getCarForSales retourne une erreur si un véhicule n’est pas trouvé', async () => {
            await expect(carForSaleService.getCarForSales([9999])).rejects.toThrow(NotFoundException)
        })

        it('createCarForSale retourne une erreur si l’utilisateur est introuvable', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: 999,
            }

            await expect(carForSaleService.createCarForSale(createCarDto)).rejects.toThrow(
                BadRequestException
            )
        })

        it('updateCarForSale met à jour un véhicule avec succès', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }
            const car = await carForSaleService.createCarForSale(createCarDto)

            const updateCarDto: UpdateCarForSaleDto = { model: 'Updated Corolla' }

            const updatedCar = await carForSaleService.updateCarForSale(car.id, updateCarDto)
            expect(updatedCar.model).toEqual('Updated Corolla')
        })

        it('deleteCarForSale supprime un véhicule avec succès', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }
            const car = await carForSaleService.createCarForSale(createCarDto)

            await carForSaleService.deleteCarForSale([car.id])

            const cars = await carForSaleService.getCarForSales([])
            expect(cars).not.toContainEqual([])
        })

        it('deleteCarForSale retourne une erreur si le véhicule est introuvable', async () => {
            await expect(carForSaleService.deleteCarForSale([9999])).rejects.toThrow(NotFoundException)
        })
    })

    describe('Controller', () => {
        it('CarForSaleController est défini', () => {
            expect(carForSaleController).toBeDefined()
        })

        it('CarForSaleController.createCarForSale crée un véhicule avec succès', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }

            const result = await carForSaleController.createCarForSale(createCarDto)

            expect(result).toBeDefined()
            expect(result.manufacturer).toEqual(createCarDto.manufacturer)
            expect(result.created_by.id).toEqual(userCreated.id)
        })

        it('CarForSaleController.getCarForSale retourne tous les véhicules', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }

            const car = await carForSaleService.createCarForSale(createCarDto)
            const result = await carForSaleController.getCarForSale([])

            expect(result).toEqual(car)
        })

        it('CarForSaleController.getCarForSale retourne le véhicule correspondant à un ID valide', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }

            const car = await carForSaleService.createCarForSale(createCarDto)
            const result = await carForSaleController.getCarForSale([car.id])

            expect(result).toBeDefined()
            expect(result.id).toEqual(car.id)
        })

        it('CarForSaleController.getCarForSale retourne une erreur pour un ID inexistant', async () => {
            await expect(carForSaleController.getCarForSale([999])).rejects.toThrow(NotFoundException)
        })

        it('CarForSaleController.updateCarForSale met à jour un véhicule avec succès', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }

            const car = await carForSaleService.createCarForSale(createCarDto)
            const updateCarDto: UpdateCarForSaleDto = { model: 'Updated Corolla' }

            const result = await carForSaleController.updateCarForSale(car.id, updateCarDto)

            expect(result.model).toEqual('Updated Corolla')
        })

        it('CarForSaleController.deleteCar supprime un véhicule avec succès', async () => {
            const createCarDto: CreateCarForSaleDto = {
                manufacturer: 'Toyota',
                model: 'Corolla',
                price: 20000,
                power: 120,
                tax_power: 5,
                fuel: 'Gasoline',
                mileage: 5000,
                conveyance_type: 'Manual',
                color: 'Blue',
                created_by: userCreated.id,
            }

            const car = await carForSaleService.createCarForSale(createCarDto)

            await carForSaleController.deleteCarForSale(car.id)

            const result = await carForSaleService.getCarForSales([])
            expect(result).toHaveLength(0)
        })

        it('CarForSaleController.deleteCar retourne une erreur pour un ID inexistant', async () => {
            await expect(carForSaleController.deleteCarForSale(999)).rejects.toThrow(NotFoundException)
        })
    })
})
