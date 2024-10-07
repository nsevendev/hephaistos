import { Module } from '@nestjs/common'
import { RoleService } from './app/role.service'
import { RoleController } from './app/role.controller'
import { RoleRepository } from './infra/role.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Role } from './domaine/role.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Role])],
    controllers: [RoleController],
    providers: [RoleService, RoleRepository],
    exports: [RoleService, RoleRepository],
})
export class RoleModule {}
