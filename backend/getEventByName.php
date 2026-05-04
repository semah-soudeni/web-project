<?php
require_once "bd.php";
session_start();

$eventName = $_POST["eventName"];
if (empty($eventName)){
    echo json_encode([
        "success" => false,
        "message" => "event name is not sent"
    ]);
    exit();
}
try {
    $connexion = ConnexionBD::getInstance();
    $query = $connexion->prepare("select e.* from events e  
                                  where title=?"
                                );
    $query->execute([$eventName]);

    $event = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $connexion->prepare("select e.email email , s.role role ,s.photo photo from staff s 
                                    join etudiant e on s.user_id = e.id
                                    join events ev on ev.id = s.event_id 
                                    where ev.title=?");
    $query->execute([$eventName]);
    $event["staff"] = $query->fetchAll(PDO::FETCH_ASSOC);


    $query = $connexion->prepare("select c.name from clubs c 
                        join club_events ce on ce.club_id = c.id 
                        join  events e on e.id = ce.event_id 
                        where e.title = ? ");
    $query->execute([$eventName]);
    $event["clubs"] = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($event);
}
catch (PDOException $e){
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()    
    ]);
}


