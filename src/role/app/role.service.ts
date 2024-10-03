import { Injectable, NotFoundException } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { RoleRepository } from '../infra/role.repository'
import { Role } from '../domaine/role.entity'

@Injectable()
export class RoleService extends BaseService {
    constructor(private readonly roleRepository: RoleRepository) {
        super('RoleService')
    }

    async getManyRoles(roleIds?: number[]): Promise<Role[]> {
        if (roleIds && roleIds.length > 0) {
            return await this.roleRepository.repository.findByIds(roleIds)
        } else {
            return await this.roleRepository.repository.find()
        }
    }

    async getOneRole(roleId: number): Promise<Role> {
        const role = await this.roleRepository.repository.findOne({ where: { id: roleId } })
        if (!role) {
            throw new NotFoundException(`Le role avec l'ID ${roleId} est introuvable.`)
        }
        return role
    }

    async createRole(name: string): Promise<Role> {
        if (!name || name.trim() === '') {
            throw new Error('Le champ name est requis.')
        }

        const newRole = this.roleRepository.repository.create({ name })
        const savedRole = await this.roleRepository.repository.save(newRole)

        return savedRole
    }

    async deleteOneRole(roleId: number): Promise<void> {
        const result = await this.roleRepository.repository.delete(roleId)
        if (result.affected === 0) {
            throw new NotFoundException(`Le role avec l'ID ${roleId} est introuvable.`)
        }
    }

    async deleteManyRoles(roleIds: number[]): Promise<void> {
        await this.roleRepository.repository.delete(roleIds)
    }
}
