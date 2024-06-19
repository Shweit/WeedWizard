<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613132223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE strain ADD breeder_id INT NOT NULL, CHANGE breeder_info breeder_info JSON NOT NULL, CHANGE parents parents JSON NOT NULL, CHANGE hybrids hybrids JSON NOT NULL, CHANGE medical medical JSON NOT NULL, CHANGE pics pics JSON NOT NULL, CHANGE reviews reviews JSON NOT NULL');
        $this->addSql('ALTER TABLE strain ADD CONSTRAINT FK_A630CD7233C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('CREATE INDEX IDX_A630CD7233C95BB1 ON strain (breeder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE strain DROP FOREIGN KEY FK_A630CD7233C95BB1');
        $this->addSql('DROP INDEX IDX_A630CD7233C95BB1 ON strain');
        $this->addSql('ALTER TABLE strain DROP breeder_id, CHANGE breeder_info breeder_info LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE parents parents LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE hybrids hybrids LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE medical medical LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE pics pics LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE reviews reviews LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
