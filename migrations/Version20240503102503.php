<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503102503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cannabis_verein (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, strasse VARCHAR(255) NOT NULL, hausnummer VARCHAR(255) NOT NULL, plz INT NOT NULL, ort VARCHAR(255) NOT NULL, adresszusatz VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, mitgliedsbeitrag NUMERIC(7, 2) NOT NULL, beschreibung VARCHAR(255) DEFAULT NULL, sonstiges VARCHAR(1023) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cannabis_verein_user (cannabis_verein_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B4B9201E23651C29 (cannabis_verein_id), INDEX IDX_B4B9201EA76ED395 (user_id), PRIMARY KEY(cannabis_verein_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cannabis_verein_user ADD CONSTRAINT FK_B4B9201E23651C29 FOREIGN KEY (cannabis_verein_id) REFERENCES cannabis_verein (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cannabis_verein_user ADD CONSTRAINT FK_B4B9201EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cannabis_verein_user DROP FOREIGN KEY FK_B4B9201E23651C29');
        $this->addSql('ALTER TABLE cannabis_verein_user DROP FOREIGN KEY FK_B4B9201EA76ED395');
        $this->addSql('DROP TABLE cannabis_verein');
        $this->addSql('DROP TABLE cannabis_verein_user');
    }
}
