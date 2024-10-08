import { Module } from '@nestjs/common'
import { UserService } from './app/user.service'
import { UserRepository } from './infra/user.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { User } from './domaine/user.entity'
import { RoleModule } from '../role/role.module'
import { UserController } from './app/user.controller'

@Module({
    imports: [TypeOrmModule.forFeature([User]), RoleModule],
    providers: [UserService, UserRepository],
    exports: [UserService, UserRepository],
    controllers: [UserController],
})
export class UserModule {}
