<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216182021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E1571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EA351E1571F7E88B ON contribution (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contribution DROP CONSTRAINT FK_EA351E1571F7E88B');
        $this->addSql('DROP INDEX IDX_EA351E1571F7E88B');
        $this->addSql('ALTER TABLE contribution DROP event_id');
    }
}
