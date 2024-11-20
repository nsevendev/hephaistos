import { Module } from '@nestjs/common'
import { InterfaceHomeService } from './app/interface-home.service'
import { InterfaceHomeRepository } from './infra/interface-home.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { InterfaceHome } from './domaine/interface-home.entity'
import { RoleModule } from '../role/role.module'
// import { InterfaceHomeController } from './app/interface-home.controller'
import { UserModule } from '../user/user.module'

@Module({
    imports: [TypeOrmModule.forFeature([InterfaceHome]), RoleModule, UserModule],
    providers: [InterfaceHomeService, InterfaceHomeRepository],
    exports: [InterfaceHomeService, InterfaceHomeRepository],
    // controllers: [InterfaceHomeController],
})
export class InterfaceHomeModule {}
