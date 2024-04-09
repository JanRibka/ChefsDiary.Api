<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409145158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE UserToken (IdUserToken INT UNSIGNED AUTO_INCREMENT NOT NULL, Domain VARCHAR(15) NOT NULL, RefreshToken VARCHAR(255) DEFAULT NULL, IdUser INT UNSIGNED NOT NULL, INDEX IDX_3BA3304EF9C28DE1 (IdUser), PRIMARY KEY(IdUserToken)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserToken ADD CONSTRAINT FK_UserToken_IdUser FOREIGN KEY (IdUser) REFERENCES User (IdUser)');
        $this->addSql('ALTER TABLE User DROP RefreshToken');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserToken DROP FOREIGN KEY FK_UserToken_IdUser');
        $this->addSql('DROP TABLE UserToken');
        $this->addSql('ALTER TABLE User ADD RefreshToken VARCHAR(255) DEFAULT NULL');
    }
}
