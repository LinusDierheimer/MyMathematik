<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120195431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(511) NOT NULL, text VARCHAR(4095) NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pending_email_verification (id INT AUTO_INCREMENT NOT NULL, secret VARCHAR(255) NOT NULL, date DATETIME NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_41FD8F38A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, money DOUBLE PRECISION NOT NULL, message VARCHAR(4096) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pending_password_reset (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, secret VARCHAR(255) NOT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_4A07AFE0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, show_name VARCHAR(255) DEFAULT NULL, email_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_answer (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, text VARCHAR(4096) NOT NULL, accepted TINYINT(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_EAFFAE404B89032C (post_id), INDEX IDX_EAFFAE40A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_answer ADD CONSTRAINT FK_EAFFAE404B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_answer ADD CONSTRAINT FK_EAFFAE40A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post_answer DROP FOREIGN KEY FK_EAFFAE404B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post_answer DROP FOREIGN KEY FK_EAFFAE40A76ED395');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE pending_email_verification');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE pending_password_reset');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE post_answer');
    }
}
