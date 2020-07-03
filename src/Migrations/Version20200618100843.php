<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618100843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE profile_profile (profile_source INT NOT NULL, profile_target INT NOT NULL, INDEX IDX_52E9749337A01814 (profile_source), INDEX IDX_52E974932E45489B (profile_target), PRIMARY KEY(profile_source, profile_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT FK_52E9749337A01814 FOREIGN KEY (profile_source) REFERENCES profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT FK_52E974932E45489B FOREIGN KEY (profile_target) REFERENCES profile (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE profile_profile');
    }
}
