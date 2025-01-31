<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131130420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule ADD hours_open_am VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD hours_close_am VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD hours_open_pm VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD hours_close_pm VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule DROP hours_start');
        $this->addSql('ALTER TABLE schedule DROP hours_end');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedule ADD hours_start VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD hours_end VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedule DROP hours_open_am');
        $this->addSql('ALTER TABLE schedule DROP hours_close_am');
        $this->addSql('ALTER TABLE schedule DROP hours_open_pm');
        $this->addSql('ALTER TABLE schedule DROP hours_close_pm');
    }
}
