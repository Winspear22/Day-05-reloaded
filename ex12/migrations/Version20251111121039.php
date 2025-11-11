<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251111121039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ex12_addresses (id INT AUTO_INCREMENT NOT NULL, address LONGTEXT NOT NULL, person_id INT DEFAULT NULL, INDEX IDX_76403A1217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ex12_bank_accounts (id INT AUTO_INCREMENT NOT NULL, iban VARCHAR(34) NOT NULL, bank_name VARCHAR(50) NOT NULL, person_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_58BC9EE7217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ex12_persons (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(64) NOT NULL, name VARCHAR(64) NOT NULL, email VARCHAR(128) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ex12_addresses ADD CONSTRAINT FK_76403A1217BBB47 FOREIGN KEY (person_id) REFERENCES ex12_persons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ex12_bank_accounts ADD CONSTRAINT FK_58BC9EE7217BBB47 FOREIGN KEY (person_id) REFERENCES ex12_persons (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ex12_addresses DROP FOREIGN KEY FK_76403A1217BBB47');
        $this->addSql('ALTER TABLE ex12_bank_accounts DROP FOREIGN KEY FK_58BC9EE7217BBB47');
        $this->addSql('DROP TABLE ex12_addresses');
        $this->addSql('DROP TABLE ex12_bank_accounts');
        $this->addSql('DROP TABLE ex12_persons');
    }
}
