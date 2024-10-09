import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { UserRepository } from '../infra/user.repository'
import { CreateUserDto } from './create-user.dto'
import * as bcrypt from 'bcrypt'
import { RoleService } from '../../role/app/role.service'
import { UpdateUserDto } from './update-user.dto'

@Injectable()
export class UserService {
    constructor(
        private readonly userRepository: UserRepository,
        private readonly roleService: RoleService
    ) {}

    getUsers = async () => {
        return await this.userRepository.repository.find()
    }

    getUser = async (id: number) => {
        const user = await this.userRepository.repository.findOne({ where: { id } })

        if (!user) {
            throw new NotFoundException(`Une erreur est survenu.`)
        }

        const role = await user.role
        user.role = role

        return user
    }

    createUser = async (createUserDto: CreateUserDto) => {
        const { role, password, username, email } = createUserDto

        const roleForUser = await this.roleService.getOneRole(role)

        if (!roleForUser) {
            throw new BadRequestException('Le rôle spécifié est introuvable.')
        }

        const hashedPassword = await bcrypt.hash(password, 10)

        const newUser = this.userRepository.repository.create({
            username,
            email,
            password: hashedPassword,
            role: roleForUser,
        })

        return await this.userRepository.repository.save(newUser)
    }

    updateUser = async (id: number, updatedData: UpdateUserDto) => {
        const existingUser = await this.getUser(id)

        const role = updatedData.role
            ? await this.roleService.getOneRole(updatedData.role)
            : await existingUser.role

        if (!role) {
            throw new BadRequestException('Le rôle spécifié est introuvable.')
        }

        const updatedUser = {
            ...existingUser,
            ...updatedData,
            role,
        }

        return this.userRepository.repository.save(updatedUser)
    }

    deleteUser = async (id: number) => {
        const user = await this.getUser(id)

        if (!user) {
            throw new NotFoundException(`Une erreur est survenu.`)
        }

        await this.userRepository.repository.remove(user)
    }

    // TODO : faire les fonction login logout et generation token
}
