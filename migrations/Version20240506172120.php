<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506172120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bud_bash (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, start DATETIME NOT NULL, address VARCHAR(255) NOT NULL, entrance_fee DOUBLE PRECISION NOT NULL, INDEX IDX_FFDBCFB5B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bud_bash_user (bud_bash_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3447F8C5F8B739E9 (bud_bash_id), INDEX IDX_3447F8C5A76ED395 (user_id), PRIMARY KEY(bud_bash_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bud_bash_check_attendance (id INT AUTO_INCREMENT NOT NULL, bud_bash_party_id INT NOT NULL, participant_id INT NOT NULL, checked_attendance TINYINT(1) NOT NULL, INDEX IDX_F537F7D6E3897903 (bud_bash_party_id), INDEX IDX_F537F7D69D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bud_bash ADD CONSTRAINT FK_FFDBCFB5B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bud_bash_user ADD CONSTRAINT FK_3447F8C5F8B739E9 FOREIGN KEY (bud_bash_id) REFERENCES bud_bash (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bud_bash_user ADD CONSTRAINT FK_3447F8C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bud_bash_check_attendance ADD CONSTRAINT FK_F537F7D6E3897903 FOREIGN KEY (bud_bash_party_id) REFERENCES bud_bash (id)');
        $this->addSql('ALTER TABLE bud_bash_check_attendance ADD CONSTRAINT FK_F537F7D69D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bud_bash DROP FOREIGN KEY FK_FFDBCFB5B03A8386');
        $this->addSql('ALTER TABLE bud_bash_user DROP FOREIGN KEY FK_3447F8C5F8B739E9');
        $this->addSql('ALTER TABLE bud_bash_user DROP FOREIGN KEY FK_3447F8C5A76ED395');
        $this->addSql('ALTER TABLE bud_bash_check_attendance DROP FOREIGN KEY FK_F537F7D6E3897903');
        $this->addSql('ALTER TABLE bud_bash_check_attendance DROP FOREIGN KEY FK_F537F7D69D1C3019');
        $this->addSql('DROP TABLE bud_bash');
        $this->addSql('DROP TABLE bud_bash_user');
        $this->addSql('DROP TABLE bud_bash_check_attendance');
    }
}
