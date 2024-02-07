<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207213401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo DROP FOREIGN KEY FK_34B0844EF9C28DE1');
        $this->addSql('DROP INDEX UNIQ_34B0844EF9C28DE1 ON UserInfo');
        $this->addSql('ALTER TABLE UserInfo CHANGE IdUser IdUser INT UNSIGNED NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo CHANGE IdUser IdUser INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE UserInfo ADD CONSTRAINT FK_34B0844EF9C28DE1 FOREIGN KEY (IdUser) REFERENCES User (IdUser) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34B0844EF9C28DE1 ON UserInfo (IdUser)');
    }
}
