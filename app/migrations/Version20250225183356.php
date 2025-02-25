<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225183356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authentication.sessions (id UUID NOT NULL, user_id UUID NOT NULL, ip_address VARCHAR(255) NOT NULL, user_agent VARCHAR(255) NOT NULL, last_access_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA8A64EEA76ED395 ON authentication.sessions (user_id)');
        $this->addSql('COMMENT ON COLUMN authentication.sessions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN authentication.sessions.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN authentication.sessions.last_access_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN authentication.sessions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE authentication.sessions ADD CONSTRAINT FK_AA8A64EEA76ED395 FOREIGN KEY (user_id) REFERENCES authentication.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authentication.sessions DROP CONSTRAINT FK_AA8A64EEA76ED395');
        $this->addSql('DROP TABLE authentication.sessions');
    }
}
