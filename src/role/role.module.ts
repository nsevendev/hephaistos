import { Module } from '@nestjs/common'
import { RoleService } from './app/role.service'
import { RoleRepository } from './infra/role.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Role } from './domaine/role.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Role])],
    providers: [RoleService, RoleRepository],
    exports: [RoleService, RoleRepository],
})
export class RoleModule {}
