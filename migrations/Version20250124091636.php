<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124091636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE engine_remap_entity (id VARCHAR(32) NOT NULL, info_description VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB82089B2DC6AD9 ON engine_remap_entity (info_description)');
        $this->addSql('COMMENT ON COLUMN engine_remap_entity.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN engine_remap_entity.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE engine_remap_entity ADD CONSTRAINT FK_FB82089B2DC6AD9 FOREIGN KEY (info_description) REFERENCES info_description_model_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE engine_remap_entity DROP CONSTRAINT FK_FB82089B2DC6AD9');
        $this->addSql('DROP TABLE engine_remap_entity');
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
    }
}
