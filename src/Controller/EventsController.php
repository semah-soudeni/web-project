<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class EventsController extends AbstractController
{
    #[Route('/events', name: 'events')]
    public function index(Request $request, EventsRepository $eventRepository): Response
    {
      $selectedClub = strtolower($request->query->get('club', 'all'));
      $allowedClubs = [
          'all',
          'aero',
          'secu',
          'ieee',
          'acm',
          'android',
          'cim',
          'theatro',
          'cineradio',
          'press',
          'lions',
          'enactus',
          'jei',
          'jci',
          'chem',
          'astro',
          '3zero'
      ];
      if (!in_array($selectedClub, $allowedClubs, true)) {
          $selectedClub = 'all';
      }

      $clubColors = [
          'secu' => '#E74E25',
          'aero' => '#3280C2',
          'ieee' => '#362B69',
          'acm' => '#7DF0CA',
          'android' => '#78DE85',
          'cim' => '#F6C011',
      ];

      $clubNames = [
          'aero' => 'Aerobotix',
          'secu' => 'Securinets',
          'ieee' => 'IEEE',
          'acm' => 'ACM',
          'android' => 'Android Club',
          'cim' => 'CIM',
      ];

      $allEvents = $eventRepository->createQueryBuilder('e')
            -> leftJoin('e.clubs', 'c')
            -> addSelect('c')
            -> orderBy('e.eventDate', 'ASC')
            -> addOrderBy('e.eventTime', 'ASC')
            -> getQuery()
            -> getResult();
      $is_registered = [];
      $res2 = [];
      $queryError = false;

      if ($this->getUser()) {
          $user = $this->getUser();
          foreach($user->getRegistrations() as $reg){
            $res2[] = $reg->getEvent()->getId();
          }
      }

      foreach ($allEvents as &$evt) {
          $is_registered[$evt->getId()] = in_array((int)$evt->getId(), $res2, true);
      }
      unset($evt);

      $events = $allEvents;
      if ($selectedClub !== 'all') {
          $events = array_values(array_filter(
              $allEvents,
              static function (array $event) use ($selectedClub): bool {
                  $slugs = array_map('trim', explode(',', strtolower((string)($event['clubs'] ?? ''))));
                  return in_array($selectedClub, $slugs, true);
              }
          ));
      }

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

      function formatEventTime(?string $time): string
      {
          if (!$time) {
              return 'TBA';
          }

          $parsed = strtotime($time);
          if ($parsed === false) {
              return 'TBA';
          }

          return date('g:i A', $parsed);
      }

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
}
