<?php

namespace App\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin")]
final class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entity_manager , private EventsRepository $events_repository ) {}

    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {

        $events = $this->events_repository->findAll();
        //var_dump($events);
        return $this->render('admin/index.html.twig', [
            "activePage" => 'admin',
            'events' => $events
        ]);
    }
    #[Route('/add', name: 'add_events')]
    public function add(Request $request): Response 
    {
        if ($request->getMethod() == "GET") {
            return $this->render('admin/addEvent.html.twig',[
                "activePage" => 'admin',
            ]);
        }
        else if ($request->getMethod() == "POST"){
            $body = $request->getPayload();
            error_log(print_r($body,true)); 
            $event = new Events();
            $event->setTitle($body["title"]);
            $event->setDuration($body["duration"]);
            $event->setDescription($body["description"]);
            $event->setEventDate($body["date"]);
            $event->setEventType($body["event_type"]);
            $event->setLocation($body["location"]);
            $event->setEventTime($body["time"]);
            $event->setPrizePool($body["prize"]);
            $event->setMaxAttendees($body["max_attendees"]);
            //$event->setParticipatingClubs($body["other_participating_clubs"]);
            //$this->entity_manager->persist($event);
            //$this->entity_manager->flush();
            return new Response(json_encode([
                "success" => true,
                "message" => "event added successfully"
            ]));
        } 
        else 
            return new Response(status:403);
    }
}
