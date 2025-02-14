<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214220525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA authentication');
        $this->addSql('CREATE TABLE authentication.codes (id UUID NOT NULL, login VARCHAR(128) NOT NULL, hash VARCHAR(128) NOT NULL, expired_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFA8F4CFAA08CB10 ON authentication.codes (login)');
        $this->addSql('COMMENT ON COLUMN authentication.codes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN authentication.codes.expired_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE authentication.codes');
    }
}
