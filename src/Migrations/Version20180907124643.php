<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180907124643 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image_forward (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, name VARCHAR(150) NOT NULL, INDEX IDX_7281785AB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_forward ADD CONSTRAINT FK_7281785AB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id INT NOT NULL, CHANGE iframe_url iframe_url VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE image_forward');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id INT DEFAULT NULL, CHANGE iframe_url iframe_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
