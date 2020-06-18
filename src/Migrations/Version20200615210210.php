<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615210210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD enterprise_id INT DEFAULT NULL, ADD advisor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A97D1AC3 FOREIGN KEY (enterprise_id) REFERENCES enterprise (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64966D3AD77 FOREIGN KEY (advisor_id) REFERENCES advisor (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A97D1AC3 ON user (enterprise_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64966D3AD77 ON user (advisor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A97D1AC3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64966D3AD77');
        $this->addSql('DROP INDEX IDX_8D93D649A97D1AC3 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64966D3AD77 ON user');
        $this->addSql('ALTER TABLE user DROP enterprise_id, DROP advisor_id');
    }
}
