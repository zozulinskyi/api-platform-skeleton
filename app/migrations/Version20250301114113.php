<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301114113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA notification');
        $this->addSql('CREATE TABLE notification.notification_users (id UUID NOT NULL, user_id UUID NOT NULL, notification_id UUID NOT NULL, read_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AAD5351AA76ED395 ON notification.notification_users (user_id)');
        $this->addSql('CREATE INDEX IDX_AAD5351AEF1A9D84 ON notification.notification_users (notification_id)');
        $this->addSql('COMMENT ON COLUMN notification.notification_users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.notification_users.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.notification_users.notification_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.notification_users.read_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification.notifications (id UUID NOT NULL, subject VARCHAR(255) NOT NULL, content TEXT NOT NULL, importance VARCHAR(64) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN notification.notifications.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.notifications.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notification.notifications.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE notification.notification_users ADD CONSTRAINT FK_AAD5351AA76ED395 FOREIGN KEY (user_id) REFERENCES authentication.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification.notification_users ADD CONSTRAINT FK_AAD5351AEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification.notifications (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification.notification_users DROP CONSTRAINT FK_AAD5351AA76ED395');
        $this->addSql('ALTER TABLE notification.notification_users DROP CONSTRAINT FK_AAD5351AEF1A9D84');
        $this->addSql('DROP TABLE notification.notification_users');
        $this->addSql('DROP TABLE notification.notifications');
    }
}
