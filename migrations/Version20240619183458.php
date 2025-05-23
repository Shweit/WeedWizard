<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619183458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, parent_id INT DEFAULT NULL, content VARCHAR(1000) NOT NULL, marker_data JSON DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C0155143A76ED395 (user_id), INDEX IDX_C0155143727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_user (blog_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6D435AD9DAE07E97 (blog_id), INDEX IDX_6D435AD9A76ED395 (user_id), PRIMARY KEY(blog_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE breeder (id INT AUTO_INCREMENT NOT NULL, seedfinder_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bud_bash (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, start DATETIME NOT NULL, address VARCHAR(255) NOT NULL, entrance_fee DOUBLE PRECISION NOT NULL, coordinates VARCHAR(255) NOT NULL, extra_info VARCHAR(255) DEFAULT NULL, INDEX IDX_FFDBCFB5B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bud_bash_user (bud_bash_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3447F8C5F8B739E9 (bud_bash_id), INDEX IDX_3447F8C5A76ED395 (user_id), PRIMARY KEY(bud_bash_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bud_bash_check_attendance (id INT AUTO_INCREMENT NOT NULL, bud_bash_party_id INT NOT NULL, participant_id INT NOT NULL, checked_attendance TINYINT(1) NOT NULL, secret_string VARCHAR(255) NOT NULL, INDEX IDX_F537F7D6E3897903 (bud_bash_party_id), INDEX IDX_F537F7D69D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE canna_consultant_threads (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, thread JSON NOT NULL, UNIQUE INDEX UNIQ_3764E92CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE canna_dose_calculator (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, experience DOUBLE PRECISION NOT NULL, intensity DOUBLE PRECISION NOT NULL, recommended_dosage DOUBLE PRECISION NOT NULL, basis_dosage INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_29C64021A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cannabis_verein (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, mitgliedsbeitrag NUMERIC(7, 2) NOT NULL, beschreibung VARCHAR(1023) DEFAULT NULL, sonstiges VARCHAR(1023) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, coordinaten VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FE486531B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE knowledge_base (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, article_name VARCHAR(255) NOT NULL, site VARCHAR(255) NOT NULL, articel_content VARCHAR(9999) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_markers (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, coordinates VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, INDEX IDX_6EA2276CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, strain_id INT NOT NULL, breeder_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, state VARCHAR(255) NOT NULL, place_of_cultivation VARCHAR(255) NOT NULL, lighting VARCHAR(255) NOT NULL, thread LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_AB030D72A76ED395 (user_id), INDEX IDX_AB030D7269B9E007 (strain_id), INDEX IDX_AB030D7233C95BB1 (breeder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strain (id INT AUTO_INCREMENT NOT NULL, breeder_id INT NOT NULL, seedfinder_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, breeder_info JSON NOT NULL, parents JSON NOT NULL, hybrids JSON NOT NULL, medical JSON NOT NULL, pics JSON NOT NULL, reviews JSON NOT NULL, INDEX IDX_A630CD7233C95BB1 (breeder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, joined_club_id INT DEFAULT NULL, created_club_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, birthdate DATE NOT NULL, username VARCHAR(255) NOT NULL, bio VARCHAR(500) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, banner VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D64957B9E303 (joined_club_id), UNIQUE INDEX UNIQ_8D93D64927731874 (created_club_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143727ACA70 FOREIGN KEY (parent_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE blog_user ADD CONSTRAINT FK_6D435AD9DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_user ADD CONSTRAINT FK_6D435AD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bud_bash ADD CONSTRAINT FK_FFDBCFB5B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bud_bash_user ADD CONSTRAINT FK_3447F8C5F8B739E9 FOREIGN KEY (bud_bash_id) REFERENCES bud_bash (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bud_bash_user ADD CONSTRAINT FK_3447F8C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bud_bash_check_attendance ADD CONSTRAINT FK_F537F7D6E3897903 FOREIGN KEY (bud_bash_party_id) REFERENCES bud_bash (id)');
        $this->addSql('ALTER TABLE bud_bash_check_attendance ADD CONSTRAINT FK_F537F7D69D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE canna_consultant_threads ADD CONSTRAINT FK_3764E92CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE canna_dose_calculator ADD CONSTRAINT FK_29C64021A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cannabis_verein ADD CONSTRAINT FK_FE486531B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE map_markers ADD CONSTRAINT FK_6EA2276CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7269B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7233C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('ALTER TABLE strain ADD CONSTRAINT FK_A630CD7233C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64957B9E303 FOREIGN KEY (joined_club_id) REFERENCES cannabis_verein (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64927731874 FOREIGN KEY (created_club_id) REFERENCES cannabis_verein (id)');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143A76ED395');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143727ACA70');
        $this->addSql('ALTER TABLE blog_user DROP FOREIGN KEY FK_6D435AD9DAE07E97');
        $this->addSql('ALTER TABLE blog_user DROP FOREIGN KEY FK_6D435AD9A76ED395');
        $this->addSql('ALTER TABLE bud_bash DROP FOREIGN KEY FK_FFDBCFB5B03A8386');
        $this->addSql('ALTER TABLE bud_bash_user DROP FOREIGN KEY FK_3447F8C5F8B739E9');
        $this->addSql('ALTER TABLE bud_bash_user DROP FOREIGN KEY FK_3447F8C5A76ED395');
        $this->addSql('ALTER TABLE bud_bash_check_attendance DROP FOREIGN KEY FK_F537F7D6E3897903');
        $this->addSql('ALTER TABLE bud_bash_check_attendance DROP FOREIGN KEY FK_F537F7D69D1C3019');
        $this->addSql('ALTER TABLE canna_consultant_threads DROP FOREIGN KEY FK_3764E92CA76ED395');
        $this->addSql('ALTER TABLE canna_dose_calculator DROP FOREIGN KEY FK_29C64021A76ED395');
        $this->addSql('ALTER TABLE cannabis_verein DROP FOREIGN KEY FK_FE486531B03A8386');
        $this->addSql('ALTER TABLE map_markers DROP FOREIGN KEY FK_6EA2276CA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72A76ED395');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7269B9E007');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7233C95BB1');
        $this->addSql('ALTER TABLE strain DROP FOREIGN KEY FK_A630CD7233C95BB1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64957B9E303');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64927731874');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE blog_user');
        $this->addSql('DROP TABLE breeder');
        $this->addSql('DROP TABLE bud_bash');
        $this->addSql('DROP TABLE bud_bash_user');
        $this->addSql('DROP TABLE bud_bash_check_attendance');
        $this->addSql('DROP TABLE canna_consultant_threads');
        $this->addSql('DROP TABLE canna_dose_calculator');
        $this->addSql('DROP TABLE cannabis_verein');
        $this->addSql('DROP TABLE knowledge_base');
        $this->addSql('DROP TABLE map_markers');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE plant');
        $this->addSql('DROP TABLE strain');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
