<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191212145529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE record CHANGE label_id label_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD is_confirmed TINYINT(1) DEFAULT NULL, ADD security_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE user SET is_confirmed = TRUE, security_token = SHA1(RAND(16))');
        $this->addSql('ALTER TABLE user CHANGE is_confirmed is_confirmed TINYINT(1) NOT NULL, CHANGE security_token security_token VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE record CHANGE label_id label_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP is_confirmed, DROP security_token, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
