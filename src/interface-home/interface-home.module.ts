import { Module } from '@nestjs/common'
import { InterfaceHomeService } from './app/interface-home.service'
import { InterfaceHomeRepository } from './infra/interface-home.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { InterfaceHome } from './domaine/interface-home.entity'
// import { InterfaceHomeController } from './app/interface-home.controller'
import { InterfaceImageModule } from '../interface-image/interface-image.module'

@Module({
    imports: [TypeOrmModule.forFeature([InterfaceHome]), InterfaceImageModule],
    providers: [InterfaceHomeService, InterfaceHomeRepository],
    exports: [InterfaceHomeService, InterfaceHomeRepository],
    // controllers: [InterfaceHomeController],
})
export class InterfaceHomeModule {}
