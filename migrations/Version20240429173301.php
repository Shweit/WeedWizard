<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429173301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE canna_dose_calculator ADD basis_dosage INT NOT NULL, DROP body_weight, DROP thc_percentage, DROP tabacco_percentage');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE canna_dose_calculator ADD body_weight DOUBLE PRECISION NOT NULL, ADD thc_percentage DOUBLE PRECISION NOT NULL, ADD tabacco_percentage DOUBLE PRECISION NOT NULL, DROP basis_dosage');
    }
}
