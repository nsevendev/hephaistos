import { Module } from '@nestjs/common'
import { InterfaceImageService } from './app/interface-image.service'
import { InterfaceImageRepository } from './infra/interface-image.repository'
import { TypeOrmModule } from '@nestjs/typeorm'
import { InterfaceImage } from './domaine/interface-image.entity'
import { InterfaceImageController } from './app/interface-image.controller'
import { AwsS3Service } from '../aws/app/aws.service'

@Module({
    imports: [TypeOrmModule.forFeature([InterfaceImage])],
    providers: [InterfaceImageService, InterfaceImageRepository, AwsS3Service],
    exports: [InterfaceImageService, InterfaceImageRepository],
    controllers: [InterfaceImageController],
})
export class InterfaceImageModule {}
