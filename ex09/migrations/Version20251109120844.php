<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251109120844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ex09_addresses (id INT AUTO_INCREMENT NOT NULL, address LONGTEXT NOT NULL, person_id INT DEFAULT NULL, INDEX IDX_BFE0C88A217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ex09_bank_accounts (id INT AUTO_INCREMENT NOT NULL, iban VARCHAR(34) NOT NULL, bank_name VARCHAR(50) NOT NULL, person_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_2820A988217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ex09_persons (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(64) NOT NULL, name VARCHAR(64) NOT NULL, email VARCHAR(128) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ex09_addresses ADD CONSTRAINT FK_BFE0C88A217BBB47 FOREIGN KEY (person_id) REFERENCES ex09_persons (id)');
        $this->addSql('ALTER TABLE ex09_bank_accounts ADD CONSTRAINT FK_2820A988217BBB47 FOREIGN KEY (person_id) REFERENCES ex09_persons (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ex09_addresses DROP FOREIGN KEY FK_BFE0C88A217BBB47');
        $this->addSql('ALTER TABLE ex09_bank_accounts DROP FOREIGN KEY FK_2820A988217BBB47');
        $this->addSql('DROP TABLE ex09_addresses');
        $this->addSql('DROP TABLE ex09_bank_accounts');
        $this->addSql('DROP TABLE ex09_persons');
    }
}
