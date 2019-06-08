<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607150508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE friends MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE friends DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE friends ADD type VARCHAR(255) NOT NULL, DROP id, DROP request_pending, DROP request_accepted');
        $this->addSql('ALTER TABLE friends ADD PRIMARY KEY (sender_id, receiver_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE friends DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE friends ADD id INT AUTO_INCREMENT NOT NULL, ADD request_pending TINYINT(1) NOT NULL, ADD request_accepted TINYINT(1) NOT NULL, DROP type');
        $this->addSql('ALTER TABLE friends ADD PRIMARY KEY (id)');
    }
}
