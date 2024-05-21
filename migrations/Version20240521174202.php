<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521174202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cannabis_verein_user DROP FOREIGN KEY FK_B4B9201E23651C29');
        $this->addSql('ALTER TABLE cannabis_verein_user DROP FOREIGN KEY FK_B4B9201EA76ED395');
        $this->addSql('DROP TABLE cannabis_verein_user');
        $this->addSql('ALTER TABLE cannabis_verein DROP FOREIGN KEY FK_FE486531A4711DF4');
        $this->addSql('DROP INDEX IDX_FE486531A4711DF4 ON cannabis_verein');
        $this->addSql('ALTER TABLE cannabis_verein ADD created_by_id INT DEFAULT NULL, DROP erstellt_von_id');
        $this->addSql('ALTER TABLE cannabis_verein ADD CONSTRAINT FK_FE486531B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE486531B03A8386 ON cannabis_verein (created_by_id)');
        $this->addSql('ALTER TABLE user ADD joined_club_id INT DEFAULT NULL, ADD created_club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64957B9E303 FOREIGN KEY (joined_club_id) REFERENCES cannabis_verein (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64927731874 FOREIGN KEY (created_club_id) REFERENCES cannabis_verein (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64957B9E303 ON user (joined_club_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64927731874 ON user (created_club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cannabis_verein_user (cannabis_verein_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B4B9201E23651C29 (cannabis_verein_id), INDEX IDX_B4B9201EA76ED395 (user_id), PRIMARY KEY(cannabis_verein_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cannabis_verein_user ADD CONSTRAINT FK_B4B9201E23651C29 FOREIGN KEY (cannabis_verein_id) REFERENCES cannabis_verein (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cannabis_verein_user ADD CONSTRAINT FK_B4B9201EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cannabis_verein DROP FOREIGN KEY FK_FE486531B03A8386');
        $this->addSql('DROP INDEX UNIQ_FE486531B03A8386 ON cannabis_verein');
        $this->addSql('ALTER TABLE cannabis_verein ADD erstellt_von_id INT NOT NULL, DROP created_by_id');
        $this->addSql('ALTER TABLE cannabis_verein ADD CONSTRAINT FK_FE486531A4711DF4 FOREIGN KEY (erstellt_von_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FE486531A4711DF4 ON cannabis_verein (erstellt_von_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64957B9E303');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64927731874');
        $this->addSql('DROP INDEX IDX_8D93D64957B9E303 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64927731874 ON user');
        $this->addSql('ALTER TABLE user DROP joined_club_id, DROP created_club_id');
    }
}
