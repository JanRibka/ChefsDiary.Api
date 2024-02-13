<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212145307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS User (
            IdUser INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
            Login VARCHAR(50) NOT NULL,
            Password VARCHAR(255) NOT NULL,
            RefreshToken VARCHAR(255) DEFAULT NULL,
            IsDisabled BIT NOT NULL DEFAULT 0,
            LoginRestrictedUntil DATETIME DEFAULT NULL)'
        );
        $this->addSql('CREATE TABLE IF NOT EXISTS UserInfo (
            IdUserInfo INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
            IdUser INT UNSIGNED NOT NULL,
            UserName VARCHAR(50) DEFAULT NULL,
            Email VARCHAR(50) DEFAULT NULL,
            Phone VARCHAR(50) DEFAULT NULL,
            CreatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            INDEX idx_UserInfo__IdUser (IdUser),
            FOREIGN KEY fx_UserInfo__IdUser (IdUser)
                REFERENCES User (IdUser))'
        );
        $this->addSql('CREATE TABLE IF NOT EXISTS UserLoginHistory (
            IdUserLoginHistory INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
            IdUser INT UNSIGNED NOT NULL,
            LoginAttemptDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            LoginSuccessful BIT NOT NULL DEFAULT 0,
            INDEX idx_UserLoginHistory__IdUser (IdUser),
            FOREIGN KEY fx_UserLoginHistory__IdUser (IdUser)
              REFERENCES ChefsDiary.User (IdUser))'
        );
        $this->addSql('CREATE TABLE IF NOT EXISTS UserRoleType (
            IdUserRoleType INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
            Code VARCHAR(20) NOT NULL,
            Name VARCHAR(50) NOT NULL,
            Description VARCHAR(255) NULL)'
        );
        $this->addSql('CREATE TABLE IF NOT EXISTS UserRoles (
            IdUserRole INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
            IdUser INT UNSIGNED NOT NULL,
            IdUserRoleType INT UNSIGNED NOT NULL,
            INDEX idx_UserRoles__IdUser (IdUser),
            FOREIGN KEY fx_UserRoles__IdUser (IdUser)
              REFERENCES ChefsDiary.User (IdUser),
            FOREIGN KEY fx_UserRoles__IdUserRoleType (IdUserRoleType)
              REFERENCES ChefsDiary.UserRoleType (IdUserRoleType))'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
