import { Module } from '@nestjs/common'
import { AdminService } from './app/admin.service'
import { AdminRepository } from './infra/admin.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { Admin } from './domaine/admin.entity'

@Module({
    imports: [TypeOrmModule.forFeature([Admin])],
    providers: [AdminService, AdminRepository],
    exports: [AdminService, AdminRepository],
})
export class AdminModule {}
