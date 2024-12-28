import { DocumentBuilder } from '@nestjs/swagger'

export const swaggerConfig = new DocumentBuilder()
    .setTitle('Hephaistos API')
    .setDescription('Application backend for Hephaistos')
    .setVersion('1.0')
    .build()
