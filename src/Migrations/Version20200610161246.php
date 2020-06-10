<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610161246 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE profile ADD advisor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F66D3AD77 FOREIGN KEY (advisor_id) REFERENCES advisor (id)');
        $this->addSql('CREATE INDEX IDX_8157AA0F66D3AD77 ON profile (advisor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0F66D3AD77');
        $this->addSql('DROP INDEX IDX_8157AA0F66D3AD77 ON profile');
        $this->addSql('ALTER TABLE profile DROP advisor_id');
    }
}
