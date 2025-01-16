<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116100745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza CHANGE description description LONGTEXT DEFAULT NULL, CHANGE price price NUMERIC(10, 2) NOT NULL, CHANGE thumbnail image_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE topping ADD additional_price NUMERIC(10, 2) NOT NULL, DROP price, CHANGE name name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza CHANGE description description VARCHAR(120) NOT NULL, CHANGE price price NUMERIC(5, 2) NOT NULL, CHANGE image_filename thumbnail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE topping ADD price NUMERIC(5, 2) NOT NULL, DROP additional_price, CHANGE name name VARCHAR(100) NOT NULL');
    }
}
