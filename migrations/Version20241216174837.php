<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216174837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contribution (id SERIAL NOT NULL, suggestion_id INT DEFAULT NULL, author_id INT NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA351E15A41BB822 ON contribution (suggestion_id)');
        $this->addSql('CREATE INDEX IDX_EA351E15F675F31B ON contribution (author_id)');
        $this->addSql('CREATE TABLE suggestion (id SERIAL NOT NULL, event_id INT NOT NULL, description TEXT NOT NULL, is_taken BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DD80F31B71F7E88B ON suggestion (event_id)');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15A41BB822 FOREIGN KEY (suggestion_id) REFERENCES suggestion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15F675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contribution DROP CONSTRAINT FK_EA351E15A41BB822');
        $this->addSql('ALTER TABLE contribution DROP CONSTRAINT FK_EA351E15F675F31B');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31B71F7E88B');
        $this->addSql('DROP TABLE contribution');
        $this->addSql('DROP TABLE suggestion');
    }
}
