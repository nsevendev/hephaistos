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
        return await this.userRepository.repository.find({ relations: ['role'] })
    }

    getUser = async (id: number) => {
        const user = await this.userRepository.repository.findOne({ where: { id }, relations: ['role'] })

        if (!user) {
            throw new NotFoundException(`Une erreur est survenu.`)
        }

        return user
    }

    createUser = async (createUserDto: CreateUserDto) => {
        const { role, password, username, email } = createUserDto

        const roleForUser = await this.roleService.getRoles([role])

        if (!roleForUser) {
            throw new BadRequestException('Le rôle spécifié est introuvable.')
        }

        const hashedPassword = await bcrypt.hash(password, 10)

        const newUser = this.userRepository.repository.create({
            username,
            email,
            password: hashedPassword,
            role: roleForUser[0],
        })

        return await this.userRepository.repository.save(newUser)
    }

    updateUser = async (id: number, updatedData: UpdateUserDto) => {
        const existingUser = await this.getUser(id)

        const role = updatedData.role
            ? (await this.roleService.getRoles([updatedData.role]))[0] ||
              (() => {
                  throw new BadRequestException('Le rôle spécifié est introuvable.')
              })()
            : existingUser.role

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
