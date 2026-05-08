<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Staff;
use App\Repository\ClubsRepository;
use App\Repository\EtudiantRepository;
use App\Repository\EventsRepository;
use DateTime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin")]
final class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entity_manager ,
        private EventsRepository $events_repository ,
        private ClubsRepository $clubs_repository ,
        private EtudiantRepository $etudiant_repository) {}

    #[Route('', name: 'app_admin')]
    public function index(): Response
    {

        $events = $this->events_repository->findAll();
        //error_log(print_r($events,true)); 
        return $this->render('admin/index.html.twig', [
            "activePage" => 'admin',
            "pageTitle" => 'Admin',
            'events' => $events
        ]);
    }
    #[Route('/add', name: 'add_events')]
    public function add(Request $request): Response
    {
        if ($request->getMethod() == "GET") {
            return $this->render('admin/addEvent.html.twig', [
                "activePage" => 'admin',
                "pageTitle" => 'Event Form'
            ]);
        }
        else if ($request->getMethod() == "POST"){
            $body = $request->request->all();
            $files = $request->files;

            $date_format = "Y-m-d";
            $time_format = "H:i";

            //error_log(print_r($body["time"],true)); 
            //error_log(print_r($files,true));

            $event = new Events();
            $event->setTitle($body["title"]);
            $event->setDuration($body["duration"]);
            $event->setDescription($body["description"]);
            $date = DateTime::createFromFormat($date_format,$body["date"]);
            $event->setEventDate($date);
            $event->setEventType($body["Event_Type"]);
            $event->setLocation($body["location"]);
            $time = DateTime::createFromFormat($time_format,$body["time"]);
            $event->setEventTime($time);
            $event->setPrizePool($body["prize"]);

            $event->setMaxAttendees((int)$body["max_attendees"]);
            $other_participating_clubs = json_decode($body["other_participating_clubs"],true);
            $staff = $body["staffmember"] ?? null ;

            
            foreach($other_participating_clubs as $key => $value){
                $club = $this->clubs_repository->findByName($value);
                $event->addClub($club);
            }

            if ($staff) {
                foreach($staff as $key => $tmp){
                    $staffMember = new Staff(); 

                    $email = $tmp["email"];
                    $role = $tmp["role"]; 
                    $user = $this->etudiant_repository->findByEmail($email);
                    if (!$user){
                        $this->addFlash("error","Please make sure that staff members have acounts !");
                        return $this->redirectToRoute('add_events');
                    }

                    $photo = $files->get("staffmember")[$key]["photo"] ?? null;
                    if ($photo && $photo->isValid()){
                        $tmp_path = $photo->getpathName();
                        $file_name = uniqid() . "_" . $photo->getClientOriginalName();

                        if ($file_name && $tmp_path && is_uploaded_file($tmp_path)){
                            $server_location = $_SERVER["DOCUMENT_ROOT"] . "/uploaded";
                            $photo->move($server_location,$file_name);
                            $staffMember->setPhoto("/uploaded/" . $file_name);
                        }
                    }
                    //error_log(print_r($photo,true));
                    
                    $staffMember->setRole($role);
                    $staffMember->setUser($user);
                    $staffMember->setEvent($event);
                    try {
                        $this->entity_manager->persist($staffMember);
                    }
                    catch (Exception $e){
                        $this->addFlash("error","An Internal Error Occured While Inserting Staff !");
                        return $this->redirectToRoute("add_events");
                    }
                }
            }
            
            try {
                $this->entity_manager->persist($event);
                $this->entity_manager->flush();
            }
            catch(UniqueConstraintViolationException $e){
                $this->addFlash("error","This event already exists !");
                return $this->redirectToRoute("add_events");
            }
            catch(Exception $e){
                $this->addFlash("error","An Internal Error Just Occured !");
                return $this->redirectToRoute("add_events");
            }

            $this->addFlash("success","The event is successfully added !");
            return $this->redirectToRoute('app_admin');
        } 
        else 
            return new Response(status:403);
    }
    #[Route("/delete",name:"delete_event")]
    public function delete(Request $request): Response{
        $body = $request->request->all(); 
        $event_title = $body["title"];

        $event = $this->events_repository->findByTitle($event_title);
        if ($event){
            $this->entity_manager->remove($event); 
            $this->entity_manager->flush();
            $this->addFlash("success","Event deleted successfully !");
        }
        $this->addFlash("error","Sorry , the requested event seems to be unavailable :(");
        return $this->redirectToRoute("app_admin");
        
    }
    #[Route("/edit", name : "edit_event")]
    public function edit(Request $request): Response
    {
        $body = $request->request->all();
        $event_title = $body["eventTitle"];

        $event = $this->events_repository->findByTitle($event_title);
        if ($event){
            $date_format = "Y-m-d";
            $time_format = "H:i";

            $event->setTitle($body["title"]);
            $event->setDuration($body["duration"]);
            $event->setDescription($body["description"]);
            $date = DateTime::createFromFormat($date_format,$body["date"]);
            $event->setEventDate($date);
            $event->setEventType($body["Event_Type"]);
            $event->setLocation($body["location"]);
            $time = DateTime::createFromFormat($time_format,$body["time"]);
            $event->setEventTime($time);
            $event->setPrizePool($body["prize"]);

            $event->setMaxAttendees((int)$body["max_attendees"]);
            $other_participating_clubs = json_decode($body["other_participating_clubs"],true);
            $staff = $body["staffmember"] ?? null ;

            $clubs = $this->clubs_repository->findAll(); 

            foreach($other_participating_clubs as $key => $value){
                $club = $this->clubs_repository->findByName($value);
                if (!in_array($club,$clubs)) $event->addClub($club);
            }

            if ($staff) {
                foreach($staff as $key => $tmp){
                    $staffMember = new Staff(); 

                    $email = $tmp["email"];
                    $role = $tmp["role"]; 
                    $user = $this->etudiant_repository->findByEmail($email);
                    if (!$user){
                        $this->addFlash("error","Please make sure that staff members have acounts !");
                        return $this->redirectToRoute('add_events');
                    }
                    $files = $request->files;
                    $photo = $files->get("staffmember")[$key]["photo"] ?? null;
                    if ($photo && $photo->isValid()){
                        $tmp_path = $photo->getpathName();
                        $file_name = uniqid() . "_" . $photo->getClientOriginalName();

                        if ($file_name && $tmp_path && is_uploaded_file($tmp_path)){
                            $server_location = $_SERVER["DOCUMENT_ROOT"] . "/uploaded";
                            $photo->move($server_location,$file_name);
                            $staffMember->setPhoto("/uploaded/" . $file_name);
                        }
                    }
                    //error_log(print_r($photo,true));
                    
                    $staffMember->setRole($role);
                    $staffMember->setUser($user);
                    $staffMember->setEvent($event);
                    try {
                        $this->entity_manager->persist($staffMember);
                    }
                    catch (Exception $e){
                        $this->addFlash("error","An Internal Error Occured While Inserting Staff !");
                        return $this->redirectToRoute("add_events");
                    }
                }
            }
            
            try {
                $this->entity_manager->persist($event);
                $this->entity_manager->flush();
            }
            catch(UniqueConstraintViolationException $e){
                $this->addFlash("error","This event already exists !");
                return $this->redirectToRoute("add_events");
            }
            catch(Exception $e){
                $this->addFlash("error","An Internal Error Just Occured !");
                return $this->redirectToRoute("add_events");
            }
            $this->addFlash("success","The event is successfully edited");
            $this->redirectToRoute("app_admin"); 
        }
        $this->addFlash("error" , "Sorry, the request event seems to be unavailable :'( ");
        return $this->redirectToRoute("app_admin"); 
    }
}
