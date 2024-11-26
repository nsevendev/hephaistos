import { Module } from '@nestjs/common'
import { AwsS3Service } from './app/aws.service'
import { TypeOrmModule } from '@nestjs/typeorm'

@Module({
    imports: [TypeOrmModule.forFeature([AwsS3Service])],
    providers: [AwsS3Service],
    exports: [AwsS3Service],
})
export class AwsServiceModule {}
