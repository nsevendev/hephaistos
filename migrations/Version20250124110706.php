<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124110706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lm_quatre_entity (id VARCHAR(32) NOT NULL, info_description VARCHAR(32) NOT NULL, owner VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number INT NOT NULL, company_create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_242D5B78B2DC6AD9 ON lm_quatre_entity (info_description)');
        $this->addSql('COMMENT ON COLUMN lm_quatre_entity.company_create_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre_entity.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre_entity.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE lm_quatre_entity ADD CONSTRAINT FK_242D5B78B2DC6AD9 FOREIGN KEY (info_description) REFERENCES info_description_model_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_sales_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER info_description TYPE VARCHAR(32)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lm_quatre_entity DROP CONSTRAINT FK_242D5B78B2DC6AD9');
        $this->addSql('DROP TABLE lm_quatre_entity');
        $this->addSql('ALTER TABLE car_sales_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
    }
}
