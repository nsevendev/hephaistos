import { Module } from '@nestjs/common'
import { PingModule } from './ping/ping.module'
import { ConfigModule } from '@nestjs/config'
import { DatabaseModule } from './database/database.module'
import { DatabaseTestModule } from './database-test/database-test.module'
import { RoleModule } from './role/role.module'
import { UserModule } from './user/user.module'
import { CarForSaleModule } from './car-for-sale/car-for-sale.module'
import { ServiceModule } from './service/service.module'
import { NotificationModule } from './notification/notification.module'
import { MechanicalServiceModule } from './mechanical-service/mechanical-service.module'
import { ChatRoomModule } from './chat-room/chat-room.module'
import { ChatModule } from './chat/chat.module'
import { AppointmentModule } from './appointment/appointment.module'
import { ContactModule } from './contact/contact.module'
import { CarForSaleImageModule } from './car-for-sale-image/car-for-sale-image.module'

@Module({
    imports: [
        ConfigModule.forRoot({ envFilePath: '.env.dev.local', isGlobal: true }),
        DatabaseModule,
        DatabaseTestModule,
        PingModule,
        RoleModule,
        UserModule,
        CarForSaleModule,
        CarForSaleImageModule,
        ServiceModule,
        NotificationModule,
        MechanicalServiceModule,
        ChatRoomModule,
        ChatModule,
        AppointmentModule,
        ContactModule,
    ],
    controllers: [],
    providers: [],
})
export class AppModule {}
