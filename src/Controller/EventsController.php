<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use App\Repository\RegisterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class EventsController extends AbstractController
{
    #[Route('/events', name: 'events')]
    public function index(Request $request, EventsRepository $eventRepository, RegisterRepository $registerRepository): Response
    {
        $selectedClub = strtolower($request->query->get('club', 'all'));
        $allowedClubs = [
            'all',
            'aero',
            'secu',
            'ieee',
            'acm',
            'cim',
            'theatro',
            'press'
        ];
        if (!in_array($selectedClub, $allowedClubs, true)) {
            $selectedClub = 'all';
        }

        $clubColors = [
            'secu' => '#E74E25',
            'aero' => '#3280C2',
            'ieee' => '#362B69',
            'acm' => '#7DF0CA',
            'theatro' => '#9B59B6',
            'cim' => '#F6C011',
        ];

        $clubNames = [
            'aero' => 'Aerobotix',
            'secu' => 'Securinets',
            'ieee' => 'IEEE',
            'acm' => 'ACM',
            'cim' => 'CIM',
            'theatro' => 'Theatro'
        ];

        $allEvents = $eventRepository->createQueryBuilder('e')
            ->leftJoin('e.clubs', 'c')
            ->addSelect('c')
            ->orderBy('e.eventDate', 'ASC')
            ->addOrderBy('e.eventTime', 'ASC')
            ->getQuery()
            ->getResult();
        $is_registered = [];

        $res2 = [];
        if ($this->getUser()) {
            /** @var Etudiant $user */
            $user = $this->getUser();
            $res2 = $registerRepository->findEventIdsByUser($user->getId());
        }
        $queryError = false;

        foreach ($allEvents as &$evt) {
            $is_registered[$evt->getId()] = in_array((int)$evt->getId(), $res2, true);
        }
        unset($evt);

        $allEvents = $eventRepository->findByClub($selectedClub);
        $events = $allEvents;

        $groupedEvents = [];
        foreach ($events as $event) {
            $dateTime = $event->getEventDate();
            if ($dateTime) {
                $monthYear = $dateTime->format('F Y');
                $sortKey = $dateTime->format('Y-m');
            } else {
                $monthYear = 'Unknown Date';
                $sortKey = '9999-12';
            }

            if (!isset($groupedEvents[$monthYear])) {
                $groupedEvents[$monthYear] = [
                    'sort' => $sortKey,
                    'items' => [],
                ];
            }

            $groupedEvents[$monthYear]['items'][] = $event;
        }

        uasort($groupedEvents, static function (array $a, array $b): int {
            return strcmp($a['sort'], $b['sort']);
        });

        /*$eventsPayload = [
          'selectedClub' => $selectedClub,
          'queryError' => $queryError,
          'events' => $allEvents,
      ];*/
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
            'pageTitle' => 'Upcoming Events',
            'activePage' => 'events',
            'selectedClub' => $selectedClub,
            'is_registered' => $is_registered,
            'clubColors' => $clubColors,
            'clubNames' => $clubNames,
            'queryError' => $queryError,
            'groupedEvents' => $groupedEvents,
        ]);
    }

    #[Route('/eventsreg/{event_id}', name: 'events_registration')]
    public function index_register(Request $request, EventsRepository $eventsRepository, \Doctrine\ORM\EntityManagerInterface $entityManager, int $event_id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('signin');
        }

        if (!$event_id) {
            return $this->redirectToRoute('events');
        }

        $event = $eventsRepository->find($event_id);
        if (!$event) {
            return $this->redirectToRoute('events');
        }

        if ($request->isMethod('POST')) {
            $register = new \App\Entity\Register();

            /** @var \App\Entity\Etudiant $user */
            $user = $this->getUser();
            $register->setUser($user);

            $register->setEvent($event);
            $register->setPaid(false); // default value

            $register->setTeamName($request->request->get('team_name'));
            $members = $request->request->get('team_nb_memebers');
            if ($members) {
                $register->setTeamNbMemebers((int)$members);
            }
            $register->setLinks($request->request->get('links'));

            $entityManager->persist($register);
            $entityManager->flush();

            return $this->redirectToRoute('events');
        }

        return $this->render('events/register.html.twig', [
            'controller_name' => 'EventsController',
            'pageTitle' => 'Register - ' . $event->getTitle(),
            'activePage' => 'events_registration',
            'event' => $event,
        ]);
    }
}
