<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207212655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo DROP FOREIGN KEY UserInfo_ibfk_1');
        $this->addSql('DROP INDEX idx_UserInfo__IdUser ON UserInfo');
        $this->addSql('ALTER TABLE UserInfo ADD UserName VARCHAR(50) DEFAULT NULL, ADD CreatedAt DATETIME NOT NULL, DROP IdUser, DROP FirstName, DROP LastName, CHANGE IdUserInfo IdUserInfo INT UNSIGNED AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo ADD IdUser INT UNSIGNED NOT NULL, ADD LastName VARCHAR(50) DEFAULT NULL, DROP CreatedAt, CHANGE IdUserInfo IdUserInfo INT AUTO_INCREMENT NOT NULL, CHANGE UserName FirstName VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE UserInfo ADD CONSTRAINT UserInfo_ibfk_1 FOREIGN KEY (IdUser) REFERENCES User (IdUser) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX idx_UserInfo__IdUser ON UserInfo (IdUser)');
    }
}
