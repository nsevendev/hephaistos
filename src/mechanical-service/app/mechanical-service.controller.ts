import { Controller } from '@nestjs/common'
import { ApiTags } from '@nestjs/swagger'
import { MechanicalService } from '../domaine/mechanical-service.entity'

@ApiTags('MechanicalService Controller')
@Controller('mechanical-service')
export class MechanicalServiceController {
    constructor(private readonly MechanicalService: MechanicalService) {}
}
