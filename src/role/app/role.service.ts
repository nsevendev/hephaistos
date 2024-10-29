import { Injectable, NotFoundException } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { RoleRepository } from '../infra/role.repository'
import { Role } from '../domaine/role.entity'
import { CreateRoleDto } from './create-role.dto'
import { In } from 'typeorm'

@Injectable()
export class RoleService extends BaseService {
    constructor(private readonly roleRepository: RoleRepository) {
        super('RoleService')
    }

    async getRoles(roleIds?: number[]): Promise<Role[]> {
        const roles =
            roleIds && roleIds.length > 0
                ? await this.roleRepository.repository.findBy({ id: In(roleIds) })
                : await this.roleRepository.repository.find()

        if (roleIds && roleIds.length > 0 && roles.length === 0) {
            throw new NotFoundException('Aucun rôle trouvé avec les IDs fournis.')
        }

        return roles
    }

    async createRole(createRoleDto: CreateRoleDto) {
        const newRole = this.roleRepository.repository.create(createRoleDto)
        return await this.roleRepository.repository.save(newRole)
    }

    async deleteRoles(roleIds: number[]) {
        const result = await this.roleRepository.repository.delete(roleIds)

        if (result.affected === 0) {
            throw new NotFoundException(`Aucun rôle trouvé pour les ID fournis.`)
        }

        const message = {
            message: `${result.affected} rôle(s) supprimée`,
            deleteCount: result.affected,
        }

        return message
    }
}
