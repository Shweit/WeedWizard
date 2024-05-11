<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511181751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cannabis_verein ADD erstellt_von_id INT NOT NULL, ADD coordinaten VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cannabis_verein ADD CONSTRAINT FK_FE486531A4711DF4 FOREIGN KEY (erstellt_von_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE486531A4711DF4 ON cannabis_verein (erstellt_von_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cannabis_verein DROP FOREIGN KEY FK_FE486531A4711DF4');
        $this->addSql('DROP INDEX IDX_FE486531A4711DF4 ON cannabis_verein');
        $this->addSql('ALTER TABLE cannabis_verein DROP erstellt_von_id, DROP coordinaten');
    }
}
