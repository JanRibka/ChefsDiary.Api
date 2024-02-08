<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208214928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE UserInfo (IdUserInfo INT UNSIGNED AUTO_INCREMENT NOT NULL, UserName VARCHAR(50) DEFAULT NULL, Email VARCHAR(50) DEFAULT NULL, Phone VARCHAR(50) DEFAULT NULL, CreatedAt DATETIME NOT NULL, IdUser INT UNSIGNED NOT NULL, UNIQUE INDEX UNIQ_34B0844EF9C28DE1 (IdUser), PRIMARY KEY(IdUserInfo)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserInfo ADD CONSTRAINT FK_34B0844EF9C28DE1 FOREIGN KEY (IdUser) REFERENCES User (IdUser)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo DROP FOREIGN KEY FK_34B0844EF9C28DE1');
        $this->addSql('DROP TABLE UserInfo');
    }
}
