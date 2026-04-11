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
    IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        photo BLOB, 
        event_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES etudiant(id),
        FOREIGN KEY (event_id) REFERENCES events(id)
    );


CREATE TABLE
    IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        club_id JSON NOT NULL,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        event_date DATE NOT NULL,
        event_time TIME DEFAULT NULL,
        location VARCHAR(200) DEFAULT NULL,
        attendees INT DEFAULT NULL CHECK (attendees <= max_attendees),
        max_attendees INT ,
        duration INT NOT NULL, --belnhar
        event_type enum('hackathon','conference','workshop','competition') NOT NULL,
        prize_pool TEXT DEFAULT NULL,

        FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
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
INSERT INTO
    events (
        club_id,
        title,
        description,
        event_date,
        event_time,
        location
    )
VALUES
    (
        1,
        'Robotics Workshop',
        'Hands-on intro to Arduino and servo motors.',
        '2025-04-10',
        '14:00:00',
        'Room B204'
    ),
    (
        2,
        'CTF Competition',
        'Capture The Flag — beginner friendly.',
        '2025-04-15',
        '09:00:00',
        'Room A110'
    ),
    (
        3,
        'IEEE Tech Talk',
        'Signal processing with Python.',
        '2025-04-20',
        '15:30:00',
        'Amphitheatre'
    ),
    (
        4,
        'ACM Coding Contest',
        'Competitive programming warm-up round.',
        '2025-04-25',
        '10:00:00',
        'Lab C302'
    ),
    (
        1,
        'Drone Flying Session',
        'Outdoor demo — bring your curiosity.',
        '2025-05-03',
        '11:00:00',
        'Campus Courtyard'
    ),
    (
        2,
        'Web Security Seminar',
        'OWASP Top 10 explained with live demos.',
        '2025-05-08',
        '14:00:00',
        'Room A110'
    );


CREATE TABLE
    etudiant (
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
    memberships (
        user_id INT NOT NULL,
        club_id INT NOT NULL,
        role ENUM ('member', 'president', 'vpa', 'vpt') DEFAULT 'member',
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, club_id),
        FOREIGN KEY (user_id) REFERENCES etudiant (id) ON DELETE CASCADE,
        FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
    ) ENGINE = InnoDB;

INSERT INTO
    etudiant (first_name, last_name, email, password, phone)
VALUES
    -- 3Zero (Emma Green, Lucas Soil, Maya Rivers)
    (
        'Emma',
        'Green',
        'emma.green@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000001'
    ),
    (
        'Lucas',
        'Soil',
        'lucas.soil@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000002'
    ),
    (
        'Maya',
        'Rivers',
        'maya.rivers@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000003'
    ),
    -- Enactus (Laura Chen, Marcus Johnson, Aisha Patel)
    (
        'Laura',
        'Chen',
        'laura.chen@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000004'
    ),
    (
        'Marcus',
        'Johnson',
        'marcus.johnson@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000005'
    ),
    (
        'Aisha',
        'Patel',
        'aisha.patel@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000006'
    ),
    -- JCI (Clark Kent, Lois Lane, Bruce Wayne)
    (
        'Clark',
        'Kent',
        'clark.kent@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000007'
    ),
    (
        'Lois',
        'Lane',
        'lois.lane@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000008'
    ),
    (
        'Bruce',
        'Wayne',
        'bruce.wayne@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000009'
    ),
    -- JEI (Alex Mercer, Diana Prince, Oliver Queen)
    (
        'Alex',
        'Mercer',
        'alex.mercer@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000010'
    ),
    (
        'Diana',
        'Prince',
        'diana.prince@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000011'
    ),
    (
        'Oliver',
        'Queen',
        'oliver.queen@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000012'
    ),
    -- Lions (Robert King, Emma Wilson, James Lee)
    (
        'Robert',
        'King',
        'robert.king@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000013'
    ),
    (
        'Emma',
        'Wilson',
        'emma.wilson@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000014'
    ),
    (
        'James',
        'Lee',
        'james.lee@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000015'
    ),
    -- Astro (Neil Armstrong, Sally Ride, Carl Sagan)
    (
        'Neil',
        'Armstrong',
        'neil.armstrong@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000016'
    ),
    (
        'Sally',
        'Ride',
        'sally.ride@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000017'
    ),
    (
        'Carl',
        'Sagan',
        'carl.sagan@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000018'
    ),
    -- Chem (Walter White, Marie Curie, Jesse Pinkman)
    (
        'Walter',
        'White',
        'walter.white@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000019'
    ),
    (
        'Marie',
        'Curie',
        'marie.curie@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000020'
    ),
    (
        'Jesse',
        'Pinkman',
        'jesse.pinkman@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000021'
    ),
    -- Android (Aymen Belhadj, Mariem Trabelsi, Yassine Khelif)
    (
        'Aymen',
        'Belhadj',
        'aymen.belhadj@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000022'
    ),
    (
        'Mariem',
        'Trabelsi',
        'mariem.trabelsi@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000023'
    ),
    (
        'Yassine',
        'Khelif',
        'yassine.khelif@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000024'
    ),
    -- CineRadio (Alex Rivers, Sam Taylor, Jordan Lee)
    (
        'Alex',
        'Rivers',
        'alex.rivers@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000025'
    ),
    (
        'Sam',
        'Taylor',
        'sam.taylor@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000026'
    ),
    (
        'Jordan',
        'Lee',
        'jordan.lee@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000027'
    ),
    -- Press (Michael Stone, Sarah Connor, David Kim)
    (
        'Michael',
        'Stone',
        'michael.stone@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000028'
    ),
    (
        'Sarah',
        'Connor',
        'sarah.connor@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000029'
    ),
    (
        'David',
        'Kim',
        'david.kim@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000030'
    ),
    -- Theatro (Jane Doe, John Smith, Alice Jones)
    (
        'Jane',
        'Doe',
        'jane.doe@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000031'
    ),
    (
        'John',
        'Smith',
        'john.smith@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000032'
    ),
    (
        'Alice',
        'Jones',
        'alice.jones@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000033'
    ),
    -- Aerobotix (John One, John Two, John Three)
    (
        'John',
        'One',
        'john.one@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000034'
    ),
    (
        'John',
        'Two',
        'john.two@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000035'
    ),
    (
        'John',
        'Three',
        'john.three@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000036'
    ),
    -- CIM (Chris Infra, Mark Maintenance, Steve Support)
    (
        'Chris',
        'Infra',
        'chris.infra@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000037'
    ),
    (
        'Mark',
        'Maintenance',
        'mark.m@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000038'
    ),
    (
        'Steve',
        'Support',
        'steve.s@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000039'
    ),
    -- ACM (Alex Computing, Mary Logic, Peter Binary)
    (
        'Alex',
        'Computing',
        'alex.c@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000040'
    ),
    (
        'Mary',
        'Logic',
        'mary.l@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000041'
    ),
    (
        'Peter',
        'Binary',
        'peter.b@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000042'
    ),
    -- IEEE (Isaac IEEE, Emma Circuits, Tom Power)
    (
        'Isaac',
        'IEEE',
        'isaac.i@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000043'
    ),
    (
        'Emma',
        'Circuits',
        'emma.c@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000044'
    ),
    (
        'Tom',
        'Power',
        'tom.p@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000045'
    ),
    -- Securinets (Alice Cyber, Bob Hack, Charlie Secu)
    (
        'Alice',
        'Cyber',
        'alice.c@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000046'
    ),
    (
        'Bob',
        'Hack',
        'bob.h@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000047'
    ),
    (
        'Charlie',
        'Secu',
        'charlie.s@insat.tn',
        '$2y$10$ullVPl0xOe1ZrT7PcEEHg.wyyOTr99uSgXrHo4M82W3HbKzo04myu',
        '21600000048'
    );

-- ----------------------------------------------------------
-- 2. Membership Assignments (Roles & 1-Year Tenure)
-- ----------------------------------------------------------
INSERT INTO
    memberships (user_id, club_id, role, joined_at)
VALUES
    -- 3Zero (ID 1,2,3 -> Club 16)
    (1, 16, 'president', '2023-01-01 10:00:00'),
    (2, 16, 'vpa', '2023-01-01 10:00:00'),
    (3, 16, 'vpt', '2023-01-01 10:00:00'),
    -- Enactus (ID 4,5,6 -> Club 11)
    (4, 11, 'president', '2023-01-01 10:00:00'),
    (5, 11, 'vpa', '2023-01-01 10:00:00'),
    (6, 11, 'vpt', '2023-01-01 10:00:00'),
    -- JCI (ID 7,8,9 -> Club 13)
    (7, 13, 'president', '2023-01-01 10:00:00'),
    (8, 13, 'vpa', '2023-01-01 10:00:00'),
    (9, 13, 'vpt', '2023-01-01 10:00:00'),
    -- JEI (ID 10,11,12 -> Club 12)
    (10, 12, 'president', '2023-01-01 10:00:00'),
    (11, 12, 'vpa', '2023-01-01 10:00:00'),
    (12, 12, 'vpt', '2023-01-01 10:00:00'),
    -- Lions (ID 13,14,15 -> Club 10)
    (13, 10, 'president', '2023-01-01 10:00:00'),
    (14, 10, 'vpa', '2023-01-01 10:00:00'),
    (15, 10, 'vpt', '2023-01-01 10:00:00'),
    -- Astro (ID 16,17,18 -> Club 15)
    (16, 15, 'president', '2023-01-01 10:00:00'),
    (17, 15, 'vpa', '2023-01-01 10:00:00'),
    (18, 15, 'vpt', '2023-01-01 10:00:00'),
    -- Chem (ID 19,20,21 -> Club 14)
    (19, 14, 'president', '2023-01-01 10:00:00'),
    (20, 14, 'vpa', '2023-01-01 10:00:00'),
    (21, 14, 'vpt', '2023-01-01 10:00:00'),
    -- Android (ID 22,23,24 -> Club 5)
    (22, 5, 'president', '2023-01-01 10:00:00'),
    (23, 5, 'vpa', '2023-01-01 10:00:00'),
    (24, 5, 'vpt', '2023-01-01 10:00:00'),
    -- CineRadio (ID 25,26,27 -> Club 8)
    (25, 8, 'president', '2023-01-01 10:00:00'),
    (26, 8, 'vpa', '2023-01-01 10:00:00'),
    (27, 8, 'vpt', '2023-01-01 10:00:00'),
    -- Press (ID 28,29,30 -> Club 9)
    (28, 9, 'president', '2023-01-01 10:00:00'),
    (29, 9, 'vpa', '2023-01-01 10:00:00'),
    (30, 9, 'vpt', '2023-01-01 10:00:00'),
    -- Theatro (ID 31,32,33 -> Club 7)
    (31, 7, 'president', '2023-01-01 10:00:00'),
    (32, 7, 'vpa', '2023-01-01 10:00:00'),
    (33, 7, 'vpt', '2023-01-01 10:00:00'),
    -- Aerobotix (ID 34,35,36 -> Club 1)
    (34, 1, 'president', '2023-01-01 10:00:00'),
    (35, 1, 'vpa', '2023-01-01 10:00:00'),
    (36, 1, 'vpt', '2023-01-01 10:00:00'),
    -- CIM (ID 37,38,39 -> Club 6)
    (37, 6, 'president', '2023-01-01 10:00:00'),
    (38, 6, 'vpa', '2023-01-01 10:00:00'),
    (39, 6, 'vpt', '2023-01-01 10:00:00'),
    -- ACM (ID 40,41,42 -> Club 4)
    (40, 4, 'president', '2023-01-01 10:00:00'),
    (41, 4, 'vpa', '2023-01-01 10:00:00'),
    (42, 4, 'vpt', '2023-01-01 10:00:00'),
    -- IEEE (ID 43,44,45 -> Club 3)
    (43, 3, 'president', '2023-01-01 10:00:00'),
    (44, 3, 'vpa', '2023-01-01 10:00:00'),
    (45, 3, 'vpt', '2023-01-01 10:00:00'),
    -- Securinets (ID 46,47,48 -> Club 2)
    (46, 2, 'president', '2023-01-01 10:00:00'),
    (47, 2, 'vpa', '2023-01-01 10:00:00'),
    (48, 2, 'vpt', '2023-01-01 10:00:00');

SET
    FOREIGN_KEY_CHECKS = 1;

SET
    FOREIGN_KEY_CHECKS = 1;
