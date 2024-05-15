<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515190440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cannabis_verein DROP strasse, DROP hausnummer, DROP plz, DROP ort, DROP adresszusatz');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cannabis_verein ADD strasse VARCHAR(255) NOT NULL, ADD hausnummer VARCHAR(255) NOT NULL, ADD plz INT NOT NULL, ADD ort VARCHAR(255) NOT NULL, ADD adresszusatz VARCHAR(255) DEFAULT NULL');
    }
}
