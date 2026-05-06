<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260506030847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clubs (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(50) NOT NULL, name VARCHAR(150) NOT NULL, UNIQUE INDEX UNIQ_A5BD3123989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(30) DEFAULT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, event_date DATE NOT NULL, event_time TIME DEFAULT NULL, duration INT NOT NULL, location VARCHAR(200) DEFAULT NULL, event_type VARCHAR(255) NOT NULL, attendees INT DEFAULT 0, max_attendees INT DEFAULT NULL, prize_pool LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_5387574A2B36786B (title), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE club_events (events_id INT NOT NULL, clubs_id INT NOT NULL, INDEX IDX_7648EBC09D6A1065 (events_id), INDEX IDX_7648EBC02FC7F5AF (clubs_id), PRIMARY KEY (events_id, clubs_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE memberships (role ENUM(\'member\', \'admin\', \'vpa\', \'vpt\'), joined_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, user_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_865A4776A76ED395 (user_id), INDEX IDX_865A477661190A32 (club_id), PRIMARY KEY (user_id, club_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE register (id INT AUTO_INCREMENT NOT NULL, paid TINYINT NOT NULL, team_name VARCHAR(300) DEFAULT NULL, team_nb_memebers INT DEFAULT NULL, links VARCHAR(1000) DEFAULT NULL, user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_5FF94014A76ED395 (user_id), INDEX IDX_5FF9401471F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE staff (id INT AUTO_INCREMENT NOT NULL, photo LONGBLOB DEFAULT NULL, role VARCHAR(40) NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_426EF392A76ED395 (user_id), INDEX IDX_426EF39271F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE club_events ADD CONSTRAINT FK_7648EBC09D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_events ADD CONSTRAINT FK_7648EBC02FC7F5AF FOREIGN KEY (clubs_id) REFERENCES clubs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A4776A76ED395 FOREIGN KEY (user_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A477661190A32 FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE register ADD CONSTRAINT FK_5FF94014A76ED395 FOREIGN KEY (user_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE register ADD CONSTRAINT FK_5FF9401471F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF392A76ED395 FOREIGN KEY (user_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF39271F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club_events DROP FOREIGN KEY FK_7648EBC09D6A1065');
        $this->addSql('ALTER TABLE club_events DROP FOREIGN KEY FK_7648EBC02FC7F5AF');
        $this->addSql('ALTER TABLE memberships DROP FOREIGN KEY FK_865A4776A76ED395');
        $this->addSql('ALTER TABLE memberships DROP FOREIGN KEY FK_865A477661190A32');
        $this->addSql('ALTER TABLE register DROP FOREIGN KEY FK_5FF94014A76ED395');
        $this->addSql('ALTER TABLE register DROP FOREIGN KEY FK_5FF9401471F7E88B');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF392A76ED395');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF39271F7E88B');
        $this->addSql('DROP TABLE clubs');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE club_events');
        $this->addSql('DROP TABLE memberships');
        $this->addSql('DROP TABLE register');
        $this->addSql('DROP TABLE staff');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
