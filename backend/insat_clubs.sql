CREATE DATABASE IF NOT EXISTS insat_clubs CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

USE insat_clubs;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS memberships;
DROP TABLE IF EXISTS etudiant;
DROP TABLE IF EXISTS clubs;
DROP TABLE IF EXISTS events;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE clubs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(50) NOT NULL UNIQUE, -- e.g. "aero", "jci"
        name VARCHAR(150) NOT NULL
) ENGINE = InnoDB;

INSERT INTO
    clubs (slug, name)
VALUES
    ('aero', 'Aerobotix'),
    ('secu', 'Securinets'),
    ('ieee', 'IEEE'),
    ('acm', 'ACM'),
    ('android', 'Android Club'),
    ('cim', 'CIM'),
    ('theatro', 'Theatro'),
    ('cineradio', 'Club Ciné-Radio'),
    ('press', 'Insat Press'),
    ('lions', 'Lions Club'),
    ('enactus', 'Club Enactus'),
    ('jei', 'Club JEI'),
    ('jci', 'Club JCI'),
    ('chem', 'Chem Club'),
    ('astro', 'Astro Club'),
    ('3zero', '3Zero Club');



CREATE TABLE
    IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL UNIQUE,
        description TEXT,
        event_date DATE NOT NULL,
        event_time TIME DEFAULT NULL,
        duration INT NOT NULL, 
        location VARCHAR(200) DEFAULT NULL,
        event_type enum('hackathon','conference','workshop','competition') NOT NULL,
        prize_pool TEXT DEFAULT NULL

    ) ;

CREATE TABLE
    IF NOT EXISTS club_events (
        event_id INT ,
        club_id INT,
        PRIMARY KEY (event_id,club_id),
        FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE,
        FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
    );
CREATE TABLE
    IF NOT EXISTS etudiant (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone VARCHAR(30) DEFAULT NULL,
        password VARCHAR(255) NOT NULL, -- bcrypt hash or plain text for seed
        role ENUM ('member', 'admin') DEFAULT 'member', -- Global roles only
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB;

CREATE TABLE
    IF NOT EXISTS register (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT  NULL,
        event_id INT NOT NULL,
        paid BOOLEAN NOT NULL,
        team_name VARCHAR(300) DEFAULT NULL,
        team_nb_memebers INT DEFAULT NULL,
        links VARCHAR(1000) DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES etudiant(id),
        FOREIGN KEY (event_id) REFERENCES events(id)
        );
CREATE TABLE
    IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        photo BLOB, 
        event_id INT NOT NULL,
        role VARCHAR(40) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES etudiant(id),
        FOREIGN KEY (event_id) REFERENCES events(id)
    );

CREATE TABLE
    IF NOT EXISTS memberships (
        user_id INT NOT NULL,
        club_id INT NOT NULL,
        role ENUM ('member', 'admin', 'vpa', 'vpt') DEFAULT 'member',
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, club_id),
        FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES etudiant (id) ON DELETE CASCADE
    ) ;
SET
    FOREIGN_KEY_CHECKS = 1;

SET
    FOREIGN_KEY_CHECKS = 1;
