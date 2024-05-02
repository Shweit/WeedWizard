<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502095910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE canna_dose_calculator (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, experience DOUBLE PRECISION NOT NULL, intensity DOUBLE PRECISION NOT NULL, recommended_dosage DOUBLE PRECISION NOT NULL, basis_dosage INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_29C64021A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE canna_dose_calculator ADD CONSTRAINT FK_29C64021A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE canna_dose_calculator DROP FOREIGN KEY FK_29C64021A76ED395');
        $this->addSql('DROP TABLE canna_dose_calculator');
    }
}
