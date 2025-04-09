<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409232203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_description_model ALTER libelle TYPE VARCHAR(75)');
        $this->addSql('ALTER TABLE info_description_model ALTER libelle TYPE VARCHAR(75)');
        $this->addSql('ALTER TABLE info_description_model ALTER description TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN info_description_model.libelle IS \'(DC2Type:app_shared_libelle)\'');
        $this->addSql('COMMENT ON COLUMN info_description_model.description IS \'(DC2Type:app_shared_description)\'');
        $this->addSql('ALTER TABLE lm_quatre ALTER owner TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE lm_quatre ALTER owner TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE lm_quatre ALTER adresse TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lm_quatre ALTER email TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE lm_quatre ALTER email TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE lm_quatre ALTER phone_number TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE lm_quatre ALTER phone_number TYPE VARCHAR(50)');
        $this->addSql('COMMENT ON COLUMN lm_quatre.owner IS \'(DC2Type:app_lm_quatre_owner)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.adresse IS \'(DC2Type:app_lm_quatre_adresse)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.email IS \'(DC2Type:app_lm_quatre_email)\'');
        $this->addSql('COMMENT ON COLUMN lm_quatre.phone_number IS \'(DC2Type:app_lm_quatre_phone_number)\'');
        $this->addSql('ALTER TABLE schedule ALTER day TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_open_am TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_close_am TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_open_pm TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_close_pm TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN schedule.day IS \'(DC2Type:app_schedule_day)\'');
        $this->addSql('COMMENT ON COLUMN schedule.hours_open_am IS \'(DC2Type:app_schedule_hours_open_am)\'');
        $this->addSql('COMMENT ON COLUMN schedule.hours_close_am IS \'(DC2Type:app_schedule_hours_close_am)\'');
        $this->addSql('COMMENT ON COLUMN schedule.hours_open_pm IS \'(DC2Type:app_schedule_hours_open_pm)\'');
        $this->addSql('COMMENT ON COLUMN schedule.hours_close_pm IS \'(DC2Type:app_schedule_hours_close_pm)\'');
        $this->addSql('ALTER TABLE terms_conditions_article ALTER title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE terms_conditions_article ALTER article TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.title IS \'(DC2Type:app_terms_conditions_article_title)\'');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.article IS \'(DC2Type:app_terms_conditions_article_article)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lm_quatre ALTER owner TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lm_quatre ALTER adresse TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lm_quatre ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lm_quatre ALTER phone_number TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN lm_quatre.owner IS NULL');
        $this->addSql('COMMENT ON COLUMN lm_quatre.adresse IS NULL');
        $this->addSql('COMMENT ON COLUMN lm_quatre.email IS NULL');
        $this->addSql('COMMENT ON COLUMN lm_quatre.phone_number IS NULL');
        $this->addSql('ALTER TABLE schedule ALTER day TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_open_am TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_close_am TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_open_pm TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE schedule ALTER hours_close_pm TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN schedule.day IS NULL');
        $this->addSql('COMMENT ON COLUMN schedule.hours_open_am IS NULL');
        $this->addSql('COMMENT ON COLUMN schedule.hours_close_am IS NULL');
        $this->addSql('COMMENT ON COLUMN schedule.hours_open_pm IS NULL');
        $this->addSql('COMMENT ON COLUMN schedule.hours_close_pm IS NULL');
        $this->addSql('ALTER TABLE terms_conditions_article ALTER title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE terms_conditions_article ALTER article TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.title IS NULL');
        $this->addSql('COMMENT ON COLUMN terms_conditions_article.article IS NULL');
        $this->addSql('ALTER TABLE info_description_model ALTER libelle TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE info_description_model ALTER description TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN info_description_model.libelle IS NULL');
        $this->addSql('COMMENT ON COLUMN info_description_model.description IS NULL');
    }
}
