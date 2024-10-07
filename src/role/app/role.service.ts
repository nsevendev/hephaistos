import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common'
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

    async getManyRoles(roleIds?: number[]): Promise<Role[]> {
        if (roleIds && roleIds.length > 0) {
            return await this.roleRepository.repository.findBy({ id: In(roleIds) })
        } else {
            return await this.roleRepository.repository.find()
        }
    }

    async getOneRole(roleId: number): Promise<Role> {
        const role = await this.roleRepository.repository.findOne({ where: { id: roleId } })
        if (!role) {
            throw new NotFoundException(`Le rôle avec l'ID ${roleId} est introuvable.`)
        }
        return role
    }

    async createRole(createRoleDto: CreateRoleDto): Promise<Role> {
        if (!createRoleDto.name || createRoleDto.name.trim() === '') {
            throw new BadRequestException('Le champ name est requis.')
        }

        const newRole = this.roleRepository.repository.create(createRoleDto)
        const savedRole = await this.roleRepository.repository.save(newRole)
        return savedRole
    }

    async deleteOneRole(roleId: number): Promise<void> {
        const result = await this.roleRepository.repository.delete(roleId)
        if (result.affected === 0) {
            throw new NotFoundException(`Le rôle avec l'ID ${roleId} est introuvable.`)
        }
    }

    async deleteManyRoles(roleIds: number[]): Promise<void> {
        const result = await this.roleRepository.repository.delete(roleIds)
        if (result.affected === 0) {
            throw new NotFoundException(`Aucun rôle trouvé pour les ID fournis.`)
        }
    }
}
