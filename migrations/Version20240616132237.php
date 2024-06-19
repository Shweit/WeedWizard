<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240616132237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ADD strain_id INT NOT NULL, ADD breeder_id INT NOT NULL, DROP strain, DROP breeder');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7269B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7233C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('CREATE INDEX IDX_AB030D7269B9E007 ON plant (strain_id)');
        $this->addSql('CREATE INDEX IDX_AB030D7233C95BB1 ON plant (breeder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7269B9E007');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7233C95BB1');
        $this->addSql('DROP INDEX IDX_AB030D7269B9E007 ON plant');
        $this->addSql('DROP INDEX IDX_AB030D7233C95BB1 ON plant');
        $this->addSql('ALTER TABLE plant ADD strain VARCHAR(255) NOT NULL, ADD breeder VARCHAR(255) NOT NULL, DROP strain_id, DROP breeder_id');
    }
}
