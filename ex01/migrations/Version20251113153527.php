<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251113153527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ex01_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(64) NOT NULL, name VARCHAR(64) NOT NULL, email VARCHAR(128) NOT NULL, enable TINYINT(1) DEFAULT 0 NOT NULL, birthdate DATETIME NOT NULL, address LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_3B9FD3C2F85E0677 (username), UNIQUE INDEX UNIQ_3B9FD3C2E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ex01_users');
    }
}
