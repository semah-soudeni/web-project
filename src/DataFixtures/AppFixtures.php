<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Clubs;
use App\Entity\Etudiant;
use App\Entity\Events;
use App\Entity\Register;
use App\Entity\Staff;
use App\Enum\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher) {}
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Clubs
        $clubsData = [
            'aero'      => 'Aerobotix',
            'secu'      => 'Securinets',
            'ieee'      => 'IEEE',
            'acm'       => 'ACM',
            'cim'       => 'CIM',
            'theatro'   => 'Theatro',
            'press'     => 'Insat Press'
        ];

        $clubs = [];
        foreach ($clubsData as $slug => $name) {
            $club = new Clubs();
            $club->setSlug($slug)->setName($name);
            $manager->persist($club);
            $clubs[$slug] = $club;
        }

        // Events
        $eventData = [
            [
                'title'       => 'Robotics Workshop',
                'description' => 'Hands-on intro to Arduino and servo motors.',
                'date'        => '2026-04-10',
                'time'        => '14:00:00',
                'duration'    => 120,
                'location'    => 'Room B204',
                'attendees'   => 0,
                'max'         => 30,
                'type'        => 'workshop',
                'prize'       => null,
                'clubs'       => ['aero'],
            ],
            [
                'title'       => 'CTF Competition',
                'description' => 'Capture The Flag — beginner friendly.',
                'date'        => '2026-04-15',
                'time'        => '09:00:00',
                'duration'    => 480,
                'location'    => 'Room A110',
                'attendees'   => 0,
                'max'         => 50,
                'type'        => 'competition',
                'prize'       => '500 TND',
                'clubs'       => ['secu'],
            ],
            [
                'title'       => 'IEEE Tech Talk',
                'description' => 'Signal processing with Python.',
                'date'        => '2026-04-20',
                'time'        => '15:30:00',
                'duration'    => 90,
                'location'    => 'Amphitheatre',
                'attendees'   => 0,
                'max'         => 150,
                'type'        => 'conference',
                'prize'       => null,
                'clubs'       => ['ieee'],
            ],
            [
                'title'       => 'ACM Coding Contest',
                'description' => 'Competitive programming warm-up round.',
                'date'        => '2026-04-25',
                'time'        => '10:00:00',
                'duration'    => 240,
                'location'    => 'Lab C302',
                'attendees'   => 0,
                'max'         => 40,
                'type'        => 'competition',
                'prize'       => '300 TND',
                'clubs'       => ['acm'],
            ],
            [
                'title'       => 'Drone Flying Session',
                'description' => 'Outdoor demo — bring your curiosity.',
                'date'        => '2026-05-03',
                'time'        => '11:00:00',
                'duration'    => 60,
                'location'    => 'Campus Courtyard',
                'attendees'   => 0,
                'max'         => null,
                'type'        => 'workshop',
                'prize'       => null,
                'clubs'       => ['aero'],
            ],
            [
                'title'       => 'Web Security Seminar',
                'description' => 'OWASP Top 10 explained with live demos.',
                'date'        => '2026-05-08',
                'time'        => '14:00:00',
                'duration'    => 120,
                'location'    => 'Room A110',
                'attendees'   => 0,
                'max'         => 60,
                'type'        => 'conference',
                'prize'       => null,
                'clubs'       => ['secu'],
            ],
            [
                'title'       => 'Theatre Workshop',
                'description' => 'Improv and scene writing for beginners.',
                'date'        => '2026-05-18',
                'time'        => '17:00:00',
                'duration'    => 120,
                'location'    => 'Arts Room',
                'attendees'   => 0,
                'max'         => 25,
                'type'        => 'workshop',
                'prize'       => null,
                'clubs'       => ['theatro'],
            ],
            [
                'title'       => 'Press Writing Lab',
                'description' => 'Journalism and editorial writing masterclass.',
                'date'        => '2026-05-22',
                'time'        => '10:00:00',
                'duration'    => 90,
                'location'    => 'Room A110',
                'attendees'   => 0,
                'max'         => 20,
                'type'        => 'workshop',
                'prize'       => null,
                'clubs'       => ['press'],
            ],
            // Multi-club events
            [
                'title'       => 'Hack & Fly',
                'description' => 'Drone hacking challenge — security meets robotics.',
                'date'        => '2026-06-05',
                'time'        => '10:00:00',
                'duration'    => 480,
                'location'    => 'Campus Courtyard',
                'attendees'   => 0,
                'max'         => 60,
                'type'        => 'competition',
                'prize'       => '1000 TND',
                'clubs'       => ['aero', 'secu'],
            ],
            [
                'title'       => 'AI & Circuits Summit',
                'description' => 'ML meets embedded systems — talks and demos.',
                'date'        => '2026-06-12',
                'time'        => '09:00:00',
                'duration'    => 360,
                'location'    => 'Amphitheatre',
                'attendees'   => 0,
                'max'         => 200,
                'type'        => 'conference',
                'prize'       => null,
                'clubs'       => ['ieee', 'acm'],
            ],
            [
                'title'       => 'INSAT Tech Hackathon',
                'description' => 'Annual 24h hackathon open to all clubs.',
                'date'        => '2026-06-20',
                'time'        => '08:00:00',
                'duration'    => 1440,
                'location'    => 'INSAT Hall',
                'attendees'   => 0,
                'max'         => 120,
                'type'        => 'hackathon',
                'prize'       => '3000 TND',
                'clubs'       => ['aero', 'secu', 'ieee', 'acm', 'cim'],
            ],
            [
                'title'       => 'Media & Code Jam',
                'description' => 'Build a short film with a live-coded soundtrack.',
                'date'        => '2026-07-10',
                'time'        => '14:00:00',
                'duration'    => 300,
                'location'    => 'Arts Room',
                'attendees'   => 0,
                'max'         => 40,
                'type'        => 'workshop',
                'prize'       => null,
                'clubs'       => ['acm', 'theatro'],
            ]
        ];

        $events = [];
        foreach ($eventData as $data) {
            $event = new Events();
            $event->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setEventDate(new \DateTime($data['date']))
                ->setEventTime(new \DateTime($data['time']))
                ->setDuration($data['duration'])
                ->setLocation($data['location'])
                ->setAttendees($data['attendees'])
                ->setMaxAttendees($data['max'])
                ->setEventType($data['type'])
                ->setPrizePool($data['prize']);

            foreach ($data['clubs'] as $slug) {
                $event->addClub($clubs[$slug]);
            }

            $manager->persist($event);
            $events[$data['title']] = $event;
        }

        // Users
        $users = [];
        $password = "password123";

        // hardcoded admin
        $admin = new Etudiant();
        $admin->setFirstName('Admin')
            ->setLastName('User')
            ->setEmail('admin@insat.ucar.tn')
            ->setPhone('21600000000')
            ->setRole(Role::ADMIN)
            ->setPassword($this->hasher->hashPassword($admin, $password));
        $manager->persist($admin);
        $users['admin@insat.ucar.tn'] = $admin;

        // Generated users
        for ($i = 0; $i <= 50; $i++) {
            $user = new Etudiant();
            $email = $faker->unique()->safeEmail();
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($email)
                ->setPhone('216' . $faker->numerify('#######'))
                ->setRole(Role::USER)
                ->setPassword($this->hasher->hashPassword($user, $password));
            $manager->persist($user);
            $users[$email] = $user;
        }

        $manager->flush();

        // Registrations
        $userList = array_values($users);
        $eventList = array_values($events);

        $usedPairs = [];
        $regCount = 0;
        while ($regCount < 30) {
            $randomUser  = $userList[array_rand($userList)];
            $randomEvent = $eventList[array_rand($eventList)];
            $pairKey = $randomUser->getEmail() . '_' . $randomEvent->getTitle();

            // Skip duplicate user+event pairs (unique constraint)
            if (isset($usedPairs[$pairKey])) {
                continue;
            }

            $usedPairs[$pairKey] = true;
            $reg = new Register();
            $reg->setUser($randomUser)
                ->setEvent($randomEvent)
                ->setPaid($faker->boolean(40)) // 40% chance paid
                ->setTeamName($faker->boolean(50) ? $faker->words(2, true) : null)
                ->setTeamNbMemebers($faker->boolean(50) ? $faker->numberBetween(2, 5) : null);
            $manager->persist($reg);
            $regCount++;
        }

        // Staff
        $usedStaffPairs = [];
        $staffCount = 0;
        while ($staffCount < 15) {
            $randomUser  = $userList[array_rand($userList)];
            $randomEvent = $eventList[array_rand($eventList)];
            $pairKey = $randomUser->getEmail() . '_' . $randomEvent->getTitle();

            if (isset($usedStaffPairs[$pairKey])) {
                continue;
            }

            $usedStaffPairs[$pairKey] = true;
            $staffRoles = ['Organizer', 'Co-Organizer', 'Logistics', 'Technical Lead', 'Speaker', 'Judge'];
            $staff = new Staff();
            $staff->setUser($randomUser)
                ->setEvent($randomEvent)
                ->setRole($faker->randomElement($staffRoles));
            $manager->persist($staff);
            $staffCount++;
        }

        $manager->flush();
    }
}
