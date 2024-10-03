import { Module } from '@nestjs/common'
import { AdminService } from './app/admin.service'
import { AdminRepository } from './infra/admin.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Admin } from './domaine/admin.entity'
import { Role } from '../role/domaine/role.entity'
import { RoleRepository } from '../role/infra/role.repository'

@Module({
    imports: [TypeOrmModule.forFeature([Admin, Role])],
    providers: [AdminService, AdminRepository, RoleRepository],
    exports: [AdminService, AdminRepository],
})
export class AdminModule {}
