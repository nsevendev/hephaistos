import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { UserRepository } from '../infra/user.repository'
import { RoleRepository } from '../../role/infra/role.repository'
import { User } from '../domaine/user.entity'
import { CreateUserDto } from './create-user.dto'
import * as bcrypt from 'bcrypt'

@Injectable()
export class UserService {
    constructor(
        private readonly userRepository: UserRepository,
        private readonly roleRepository: RoleRepository
    ) {}

    async createUser(createUserDto: CreateUserDto): Promise<User> {
        const { roleId, password } = createUserDto

        const userRole = await this.roleRepository.repository.findOne({ where: { id: roleId } })
        if (!userRole) {
            throw new BadRequestException('Le rôle spécifié est introuvable.')
        }

        const hashedPassword = await bcrypt.hash(password, 10)

        const newUser = this.userRepository.repository.create({
            ...createUserDto,
            password: hashedPassword,
            role: userRole,
        })

        try {
            return await this.userRepository.repository.save(newUser)
        } catch (error) {
            console.error("Erreur lors de la création de l'utilisateur:", error)
            throw new BadRequestException("Erreur lors de la création de l'utilisateur.")
        }
    }

    async getUsers(): Promise<User[]> {
        try {
            return await this.userRepository.repository.find({ relations: ['role'] })
        } catch {
            throw new BadRequestException('Erreur lors de la récupération des utilisateurs.')
        }
    }

    async getUser(id: number): Promise<User> {
        const user = await this.userRepository.repository.findOne({ where: { id }, relations: ['role'] })
        if (!user) {
            throw new NotFoundException(`L'utilisateur avec l'ID ${id} est introuvable.`)
        }
        return user
    }

    async updateUser(id: number, updatedData: Partial<User>): Promise<User> {
        const existingUser = await this.getUser(id)

        const updatedUser = this.userRepository.repository.merge(existingUser, updatedData)

        await this.userRepository.repository.save(updatedUser)

        return updatedUser
    }

    async deleteUser(id: number): Promise<void> {
        const user = await this.getUser(id)

        await this.userRepository.repository.remove(user)
    }
}
// faire les fonction login logout et generation token
