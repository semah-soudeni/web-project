<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507192013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_717E22E3E7927C74 ON etudiant (email)');
        $this->addSql('ALTER TABLE memberships CHANGE role role ENUM(\'member\', \'admin\', \'vpa\', \'vpt\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_717E22E3E7927C74 ON etudiant');
        $this->addSql('ALTER TABLE memberships CHANGE role role ENUM(\'member\', \'admin\', \'vpa\', \'vpt\') DEFAULT NULL');
    }
}
