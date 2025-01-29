<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129101426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE terms_conditions_article (id UUID NOT NULL, terms_conditions UUID NOT NULL, title VARCHAR(255) NOT NULL, article VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D07C713B7BF59952 ON terms_conditions_article (terms_conditions)');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.terms_conditions IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE terms_conditions_article ADD CONSTRAINT FK_D07C713B7BF59952 FOREIGN KEY (terms_conditions) REFERENCES terms_conditions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE terms_conditions_article DROP CONSTRAINT FK_D07C713B7BF59952');
        $this->addSql('DROP TABLE terms_conditions_article');
    }
}
