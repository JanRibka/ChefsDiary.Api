<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207211528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo ADD name INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE UserInfo ADD CONSTRAINT FK_34B0844E5E237E06 FOREIGN KEY (name) REFERENCES User (IdUser)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34B0844E5E237E06 ON UserInfo (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE UserInfo DROP FOREIGN KEY FK_34B0844E5E237E06');
        $this->addSql('DROP INDEX UNIQ_34B0844E5E237E06 ON UserInfo');
        $this->addSql('ALTER TABLE UserInfo DROP name');
    }
}
