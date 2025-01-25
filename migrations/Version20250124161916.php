<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124161916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car_sales ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE schedule ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE schedule ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop ALTER info_description_model TYPE VARCHAR(32)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedule ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre ALTER info_description_model TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap ALTER info_description_model TYPE VARCHAR(32)');
    }
}
