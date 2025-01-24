<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124124756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('ALTER TABLE lm_quatre_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre_entity ALTER info_description TYPE VARCHAR(32)');
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
        $this->addSql('ALTER TABLE ping_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE info_description_model_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE engine_remap_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE work_shop_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE car_sales_entity ALTER info_description TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre_entity ALTER id TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE lm_quatre_entity ALTER info_description TYPE VARCHAR(32)');
    }
}
