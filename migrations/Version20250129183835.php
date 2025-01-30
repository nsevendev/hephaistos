<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129183835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE terms_conditions_article DROP CONSTRAINT fk_d07c713b7bf59952');
        $this->addSql('DROP INDEX uniq_d07c713b7bf59952');
        $this->addSql('ALTER TABLE terms_conditions_article RENAME COLUMN terms_conditions TO terms_conditions_id');
        $this->addSql('ALTER TABLE terms_conditions_article ADD CONSTRAINT FK_D07C713B642D0855 FOREIGN KEY (terms_conditions_id) REFERENCES terms_conditions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D07C713B642D0855 ON terms_conditions_article (terms_conditions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE terms_conditions_article DROP CONSTRAINT FK_D07C713B642D0855');
        $this->addSql('DROP INDEX IDX_D07C713B642D0855');
        $this->addSql('ALTER TABLE terms_conditions_article RENAME COLUMN terms_conditions_id TO terms_conditions');
        $this->addSql('ALTER TABLE terms_conditions_article ADD CONSTRAINT fk_d07c713b7bf59952 FOREIGN KEY (terms_conditions) REFERENCES terms_conditions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_d07c713b7bf59952 ON terms_conditions_article (terms_conditions)');
    }
}
