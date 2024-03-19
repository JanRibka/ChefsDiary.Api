<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319120911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE User (IdUser INT UNSIGNED AUTO_INCREMENT NOT NULL, Uuid VARCHAR(36) NOT NULL, Login VARCHAR(25) NOT NULL, Password VARCHAR(75) NOT NULL, RefreshToken VARCHAR(255) DEFAULT NULL, IsDisabled TINYINT(1) DEFAULT 0 NOT NULL, LoginRestrictedUntil DATETIME DEFAULT NULL, PRIMARY KEY(IdUser)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserInfo (IdUserInfo INT UNSIGNED AUTO_INCREMENT NOT NULL, FirstName VARCHAR(25) DEFAULT NULL, LastName VARCHAR(25) DEFAULT NULL, Email VARCHAR(50) NOT NULL, Phone VARCHAR(25) DEFAULT NULL, CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, IdUser INT UNSIGNED NOT NULL, UNIQUE INDEX UNIQ_34B0844EF9C28DE1 (IdUser), PRIMARY KEY(IdUserInfo)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserLogHistory (IdUserLogHistory INT UNSIGNED AUTO_INCREMENT NOT NULL, LoginAttemptDate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, LoginSuccessful TINYINT(1) DEFAULT 0 NOT NULL, IdUser INT UNSIGNED NOT NULL, INDEX IDX_CB8BAA07F9C28DE1 (IdUser), PRIMARY KEY(IdUserLogHistory)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserRoleType (IdUserRoleType INT UNSIGNED AUTO_INCREMENT NOT NULL, Code VARCHAR(20) NOT NULL, Value SMALLINT NOT NULL, Description VARCHAR(20) NOT NULL, PRIMARY KEY(IdUserRoleType)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserRoles (IdUserRole INT UNSIGNED AUTO_INCREMENT NOT NULL, IdUser INT UNSIGNED NOT NULL, IdUserRoleType INT UNSIGNED NOT NULL, INDEX IDX_D2AABFB2F9C28DE1 (IdUser), INDEX IDX_D2AABFB2932C2497 (IdUserRoleType), PRIMARY KEY(IdUserRole)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserInfo ADD CONSTRAINT FK_34B0844EF9C28DE1 FOREIGN KEY (IdUser) REFERENCES User (IdUser)');
        $this->addSql('ALTER TABLE UserLogHistory ADD CONSTRAINT FK_CB8BAA07F9C28DE1 FOREIGN KEY (IdUser) REFERENCES User (IdUser)');
        $this->addSql('ALTER TABLE UserRoles ADD CONSTRAINT FK_D2AABFB2F9C28DE1 FOREIGN KEY (IdUser) REFERENCES User (IdUser)');
        $this->addSql('ALTER TABLE UserRoles ADD CONSTRAINT FK_D2AABFB2932C2497 FOREIGN KEY (IdUserRoleType) REFERENCES UserRoleType (IdUserRoleType)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo DROP FOREIGN KEY FK_34B0844EF9C28DE1');
        $this->addSql('ALTER TABLE UserLogHistory DROP FOREIGN KEY FK_CB8BAA07F9C28DE1');
        $this->addSql('ALTER TABLE UserRoles DROP FOREIGN KEY FK_D2AABFB2F9C28DE1');
        $this->addSql('ALTER TABLE UserRoles DROP FOREIGN KEY FK_D2AABFB2932C2497');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE UserInfo');
        $this->addSql('DROP TABLE UserLogHistory');
        $this->addSql('DROP TABLE UserRoleType');
        $this->addSql('DROP TABLE UserRoles');
    }
}
