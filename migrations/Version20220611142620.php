<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220611142620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_agent (id INT AUTO_INCREMENT NOT NULL, os VARCHAR(255) DEFAULT NULL, architecture VARCHAR(255) NOT NULL, browser VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log ADD user_agent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5D499950B FOREIGN KEY (user_agent_id) REFERENCES user_agent (id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5D499950B ON log (user_agent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5D499950B');
        $this->addSql('DROP TABLE user_agent');
        $this->addSql('DROP INDEX IDX_8F3F68C5D499950B ON log');
        $this->addSql('ALTER TABLE log DROP user_agent_id');
    }
}
