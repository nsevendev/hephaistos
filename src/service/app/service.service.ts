import { Injectable } from '@nestjs/common'
import { ServiceRepository } from '../infra/service.repository'
import { UserRepository } from '../../user/infra/user.repository'

@Injectable()
export class ServiceService {
    constructor(
        private readonly serviceRepository: ServiceRepository,
        private readonly userRepository: UserRepository
    ) {}
}
