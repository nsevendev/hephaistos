import { Module } from '@nestjs/common'
import { PingModule } from './ping/ping.module'
import { ConfigModule } from '@nestjs/config'
import { DatabaseModule } from './database/database.module'
import { DatabaseTestModule } from './database-test/database-test.module'
import { RoleModule } from './role/role.module'
import { UserModule } from './user/user.module'
import { ServiceModule } from './service/service.module'

@Module({
    imports: [
        ConfigModule.forRoot({ envFilePath: '.env.dev.local', isGlobal: true }),
        DatabaseModule,
        DatabaseTestModule,
        PingModule,
        RoleModule,
        UserModule,
        ServiceModule,
    ],
    controllers: [],
    providers: [],
})
export class AppModule {}
