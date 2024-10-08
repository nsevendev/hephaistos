import { DataSource } from 'typeorm'
import * as path from 'path'

/**
 * DataSource pour les migrations
 * ce datasource reflete la config typeorm pour la database principal
 * il doit porter le nom de dataSource pour que typeorm le reconnaisse
 * et l'utilise pour la creation des migrations
 */
export const dataSource = new DataSource({
    type: 'postgres',
    url: process.env.DATABASE_URL,
    entities: [path.join(__dirname, '**', '*.entity.{ts,js}')],
    migrations: [path.join(__dirname, 'migrations', '*.{ts,js}')],
    migrationsTableName: 'table_migrations',
    migrationsRun: false,
})
