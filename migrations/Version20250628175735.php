<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628175735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE blog ADD created_by_id INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL, ADD slug VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE blog ADD CONSTRAINT FK_C0155143B03A8386 FOREIGN KEY (created_by_id) REFERENCES `admin` (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C0155143B03A8386 ON blog (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_BLOG_SLUG ON blog (slug)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE blog DROP FOREIGN KEY FK_C0155143B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C0155143B03A8386 ON blog
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BLOG_SLUG ON blog
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE blog DROP created_by_id, DROP created_at, DROP is_active, DROP slug
        SQL);
    }
}
