<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315120041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ping ALTER status TYPE INT USING status::integer');
        $this->addSql('ALTER TABLE ping ALTER message TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN ping.status IS \'(DC2Type:app_ping_status)\'');
        $this->addSql('COMMENT ON COLUMN ping.message IS \'(DC2Type:app_ping_message)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ping ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE ping ALTER message TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN ping.status IS NULL');
        $this->addSql('COMMENT ON COLUMN ping.message IS NULL');
    }
}
