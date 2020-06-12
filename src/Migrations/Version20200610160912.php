<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610160912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE advisor (id INT AUTO_INCREMENT NOT NULL, is_already_board_member TINYINT(1) DEFAULT NULL, linkedin_link VARCHAR(300) DEFAULT NULL, cv_link VARCHAR(300) DEFAULT NULL, payment_status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, enterprise_id INT DEFAULT NULL, is_request TINYINT(1) NOT NULL, is_propose TINYINT(1) NOT NULL, payment_type VARCHAR(255) NOT NULL, title VARCHAR(45) NOT NULL, INDEX IDX_8157AA0FA97D1AC3 (enterprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_skill (profile_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_A9E97BA5CCFA12B8 (profile_id), INDEX IDX_A9E97BA55585C142 (skill_id), PRIMARY KEY(profile_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA97D1AC3 FOREIGN KEY (enterprise_id) REFERENCES enterprise (id)');
        $this->addSql('ALTER TABLE profile_skill ADD CONSTRAINT FK_A9E97BA5CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profile_skill ADD CONSTRAINT FK_A9E97BA55585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE profile_skill DROP FOREIGN KEY FK_A9E97BA5CCFA12B8');
        $this->addSql('DROP TABLE advisor');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE profile_skill');
    }
}
