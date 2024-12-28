import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
import { UserRepository } from '../infra/user.repository'
import { CreateUserDto } from './create-user.dto'
import * as bcrypt from 'bcrypt'
import { RoleService } from '../../role/app/role.service'
import { UpdateUserDto } from './update-user.dto'
import { User } from '../domaine/user.entity'
import { In } from 'typeorm'

@Injectable()
export class UserService {
    constructor(
        private readonly userRepository: UserRepository,
        private readonly roleService: RoleService
    ) {}

    getUsers = async (userIds?: number[]): Promise<User[]> => {
        const users =
            userIds && userIds.length > 0
                ? await this.userRepository.repository.find({
                      where: { id: In(userIds) },
                      relations: ['role'],
                  })
                : await this.userRepository.repository.find({ relations: ['role'] })

        if (userIds && userIds.length > 0 && users.length === 0) {
            throw new NotFoundException('Aucun utilisateur trouvé avec les IDs fournis.')
        }

        return users
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
        const existingUsers = await this.getUsers([id])
        const existingUser = existingUsers[0]

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

    async deleteUsers(userIds: number[]) {
        const result = await this.userRepository.repository.delete(userIds)

        if (result.affected === 0) {
            throw new NotFoundException(`Aucun utilisateur trouvé pour les ID fournis.`)
        }

        return result
    }

    // TODO : faire les fonction login logout et generation token
}
