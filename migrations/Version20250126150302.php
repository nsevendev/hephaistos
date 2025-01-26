<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250126150302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car_sales (id UUID NOT NULL, info_description_model UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B42AF59311469011 ON car_sales (info_description_model)');
        $this->addSql('COMMENT ON COLUMN car_sales.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN car_sales.info_description_model IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN car_sales.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN car_sales.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE engine_remap (id UUID NOT NULL, info_description_model UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_84CE46111469011 ON engine_remap (info_description_model)');
        $this->addSql('COMMENT ON COLUMN engine_remap.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN engine_remap.info_description_model IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN engine_remap.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN engine_remap.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE info_description_model (id UUID NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN info_description_model.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN info_description_model.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN info_description_model.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE lm_quatre (id UUID NOT NULL, info_description_model UUID NOT NULL, owner VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, company_create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3D225B811469011 ON lm_quatre (info_description_model)');
        $this->addSql('COMMENT ON COLUMN lm_quatre.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.info_description_model IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.company_create_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ping (id UUID NOT NULL, status VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN ping.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ping.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ping.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE schedule (id UUID NOT NULL, day VARCHAR(255) NOT NULL, hours_start VARCHAR(255) NOT NULL, hours_end VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN schedule.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN schedule.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN schedule.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE work_shop (id UUID NOT NULL, info_description_model UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55A9FD9A11469011 ON work_shop (info_description_model)');
        $this->addSql('COMMENT ON COLUMN work_shop.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_shop.info_description_model IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_shop.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN work_shop.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE car_sales ADD CONSTRAINT FK_B42AF59311469011 FOREIGN KEY (info_description_model) REFERENCES info_description_model (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE engine_remap ADD CONSTRAINT FK_84CE46111469011 FOREIGN KEY (info_description_model) REFERENCES info_description_model (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lm_quatre ADD CONSTRAINT FK_C3D225B811469011 FOREIGN KEY (info_description_model) REFERENCES info_description_model (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_shop ADD CONSTRAINT FK_55A9FD9A11469011 FOREIGN KEY (info_description_model) REFERENCES info_description_model (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car_sales DROP CONSTRAINT FK_B42AF59311469011');
        $this->addSql('ALTER TABLE engine_remap DROP CONSTRAINT FK_84CE46111469011');
        $this->addSql('ALTER TABLE lm_quatre DROP CONSTRAINT FK_C3D225B811469011');
        $this->addSql('ALTER TABLE work_shop DROP CONSTRAINT FK_55A9FD9A11469011');
        $this->addSql('DROP TABLE car_sales');
        $this->addSql('DROP TABLE engine_remap');
        $this->addSql('DROP TABLE info_description_model');
        $this->addSql('DROP TABLE lm_quatre');
        $this->addSql('DROP TABLE ping');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE work_shop');
    }
}
