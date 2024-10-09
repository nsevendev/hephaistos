import { Controller } from '@nestjs/common'
import { ServiceService } from './service.service'
import { ApiTags } from '@nestjs/swagger'

@ApiTags('Service Controller')
@Controller('service')
export class ServiceController {
    constructor(private readonly serviceService: ServiceService) {}
}
