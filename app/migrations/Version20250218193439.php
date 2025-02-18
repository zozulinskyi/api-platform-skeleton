<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218193439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authentication.change_email_requests (id UUID NOT NULL, user_id UUID NOT NULL, status VARCHAR(255) NOT NULL, old_email VARCHAR(255) NOT NULL, new_email VARCHAR(255) NOT NULL, old_email_hash VARCHAR(128) NOT NULL, new_email_hash VARCHAR(128) NOT NULL, expired_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EBBDB184A76ED395 ON authentication.change_email_requests (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHANGE_EMAIL_REQUEST ON authentication.change_email_requests (user_id, old_email, new_email)');
        $this->addSql('COMMENT ON COLUMN authentication.change_email_requests.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN authentication.change_email_requests.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN authentication.change_email_requests.expired_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN authentication.change_email_requests.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN authentication.change_email_requests.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE authentication.change_email_requests ADD CONSTRAINT FK_EBBDB184A76ED395 FOREIGN KEY (user_id) REFERENCES authentication.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authentication.change_email_requests DROP CONSTRAINT FK_EBBDB184A76ED395');
        $this->addSql('DROP TABLE authentication.change_email_requests');
    }
}
