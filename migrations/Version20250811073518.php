<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250811073518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD updated_at DATETIME DEFAULT NOW()');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169B03A8386 FOREIGN KEY (created_by_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE INDEX IDX_7332E169B03A8386 ON services (created_by_id)');
        $this->addSql('CREATE INDEX IDX_7332E169896DBBDE ON services (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169B03A8386');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169896DBBDE');
        $this->addSql('DROP INDEX IDX_7332E169B03A8386 ON services');
        $this->addSql('DROP INDEX IDX_7332E169896DBBDE ON services');
        $this->addSql('ALTER TABLE services DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
    }
}
