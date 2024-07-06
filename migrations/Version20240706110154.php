<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706110154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Check the platform and adjust SQL accordingly
        $platform = $this->connection->getDatabasePlatform();

        if ($platform instanceof MySQLPlatform) {
            $charset = ' DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
            $autoIncrement = 'AUTO_INCREMENT';
        } else {
            $charset = '';
            $autoIncrement = '';
        }

        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY ' . $autoIncrement . ', name VARCHAR(255) NOT NULL)' . $charset);
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY ' . $autoIncrement . ', title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, category_id INTEGER NOT NULL, author_id INTEGER NOT NULL, UNIQUE (slug), FOREIGN KEY (category_id) REFERENCES category (id), FOREIGN KEY (author_id) REFERENCES user (id))' . $charset);
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY ' . $autoIncrement . ', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE (email))' . $charset);
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT PRIMARY KEY ' . $autoIncrement . ', body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)' . $charset);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
