<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128133340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE terms_conditions (id UUID NOT NULL, info_description_model UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7BF5995211469011 ON terms_conditions (info_description_model)');
        $this->addSql('COMMENT ON COLUMN terms_conditions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions.info_description_model IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE terms_conditions ADD CONSTRAINT FK_7BF5995211469011 FOREIGN KEY (info_description_model) REFERENCES info_description_model (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE terms_conditions DROP CONSTRAINT FK_7BF5995211469011');
        $this->addSql('DROP TABLE terms_conditions');
    }
}
