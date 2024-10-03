import { Injectable } from '@nestjs/common'
import { BaseService } from '../../shared/base-service/base.service'
import { AdminRepository } from '../infra/admin.repository'
import { RoleRepository } from '../../role/infra/role.repository'
import * as bcrypt from 'bcrypt'
import { Admin } from '../domaine/admin.entity'

@Injectable()
export class AdminService extends BaseService {
    constructor(
        private readonly adminRepository: AdminRepository,
        private readonly roleRepository: RoleRepository // Injection du RoleRepository
    ) {
        super('AdminService')
    }

    async getAdmins(): Promise<Admin[]> {
        return await this.adminRepository.repository.find()
    }

    async createAdmin(adminData: {
        username: string
        email: string
        password: string
        roleId: number
    }): Promise<Admin> {
        const hashedPassword = await bcrypt.hash(adminData.password, 10)

        // Utilisation de la méthode findOneById
        const role = await this.roleRepository.findOneById(adminData.roleId)
        if (!role) {
            throw new Error('Rôle non trouvé.')
        }

        const admin = this.adminRepository.repository.create({
            username: adminData.username,
            email: adminData.email,
            password: hashedPassword,
            role: role,
        })
        return await this.adminRepository.repository.save(admin)
    }

    async getAdmin(id: number): Promise<Admin> {
        const admin = await this.adminRepository.repository.findOne({ where: { id } })
        if (!admin) {
            throw new Error(`L'admin avec l'ID ${id} est introuvable.`)
        }
        return admin
    }

    async updateAdmin(id: number, updatedData: Partial<Admin>): Promise<Admin> {
        await this.adminRepository.repository.update(id, updatedData)
        return await this.getAdmin(id)
    }

    async deleteAdmin(id: number): Promise<void> {
        const result = await this.adminRepository.repository.delete(id)
        if (result.affected === 0) {
            throw new Error(`L'admin avec l'ID ${id} est introuvable.`)
        }
    }

    // faire les fonction login et logout + fonction generation de token
}
