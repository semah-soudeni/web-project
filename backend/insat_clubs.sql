-- ============================================================
-- INSAT Clubs – Database Schema
-- Import via phpMyAdmin or: mysql -u root -p < insat_clubs.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS insat_clubs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE insat_clubs;

-- ----------------------------------------------------------
-- users
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    phone      VARCHAR(30)  DEFAULT NULL,
    password   VARCHAR(255) NOT NULL,           -- bcrypt hash
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- clubs  (pre-seeded — one row per club page)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS clubs (
    id   INT         AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,           -- e.g. "aero", "jci"
    name VARCHAR(150) NOT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO clubs (slug, name) VALUES
    ('aero',      'Aerobotix'),
    ('secu',      'Securinets'),
    ('ieee',      'IEEE'),
    ('acm',       'ACM'),
    ('android',   'Android Club'),
    ('cim',       'CIM'),
    ('theatro',   'Theatro'),
    ('cineradio', 'Club Ciné-Radio'),
    ('press',     'Insat Press'),
    ('lions',     'Lions Club'),
    ('enactus',   'Club Enactus'),
    ('jei',       'Club JEI'),
    ('jci',       'Club JCI'),
    ('chem',      'Chem Club'),
    ('astro',     'Astro Club'),
    ('3zero',     '3Zero Club');

-- ----------------------------------------------------------
-- memberships  (which user joined which club)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS memberships (
    user_id    INT         NOT NULL,
    club_id    INT         NOT NULL,
    joined_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, club_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (club_id) REFERENCES clubs(id) ON DELETE CASCADE
) ENGINE=InnoDB;
