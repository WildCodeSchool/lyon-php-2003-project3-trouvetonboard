<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617100124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE profile ADD date_creation DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD gender VARCHAR(45) DEFAULT NULL, ADD nationality VARCHAR(100) DEFAULT NULL, ADD birthday DATE DEFAULT NULL, ADD phone VARCHAR(100) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD post_code INT DEFAULT NULL, ADD country VARCHAR(100) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, ADD picture_link VARCHAR(300) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE profile DROP date_creation');
        $this->addSql('ALTER TABLE user DROP gender, DROP nationality, DROP birthday, DROP phone, DROP address, DROP post_code, DROP country, DROP city, DROP picture_link');
    }
}
