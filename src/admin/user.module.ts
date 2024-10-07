import { Module } from '@nestjs/common'
import { UserService } from './app/user.service'
import { UserRepository } from './infra/user.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { User } from './domaine/user.entity'
import { Role } from '../role/domaine/role.entity'
import { RoleRepository } from '../role/infra/role.repository'

@Module({
    imports: [TypeOrmModule.forFeature([User, Role])],
    providers: [UserService, UserRepository, RoleRepository],
    exports: [UserService, UserRepository, RoleRepository],
})
export class UserModule {}
