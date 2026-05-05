<?php
require_once 'bd.php';

function fetchEventsData(){
    $connexion = ConnexionBD::getInstance();
    
    $user_id = $_SESSION["id"];
    $request = $connexion->prepare("select club_id from memberships where user_id = ?");
    $request->execute([$user_id]);
    $club = $request->fetch(PDO::FETCH_ASSOC)["club_id"];
    echo "<script>console.log(".json_encode($user_id).")</script>" ;
   

    $request = $connexion->query("select * from club_events where club_id = $club");
    $events_ids = $request->fetchAll(PDO::FETCH_ASSOC);
    
    $tmpEvents = array_map(function($event){
        return $event["event_id"];
    },$events_ids);
    
    $events=[];
    foreach ($tmpEvents as $key => $value) {
        $request = $connexion->prepare("select * from club_events ce join clubs c on c.id = ce.club_id  where event_id = ?");
        $request->execute([$value]);
        $clubs = $request->fetchAll(PDO::FETCH_ASSOC);
        $clubs = array_map(function($club){
            return $club["name"];
        },$clubs);
        $request = $connexion->prepare("select * from events where id = ?");
        $request->execute([$value]);
        $event_info = $request->fetch(PDO::FETCH_ASSOC);

        $request = $connexion->prepare("select * from staff where event_id = ?");
        $request->execute([$value]);
        $staff = $request->fetchAll(PDO::FETCH_ASSOC);

        array_push($events,[
            "staff" => $staff,
            "event" => $event_info,
            "clubs" => $clubs
        ]); 
       
    }
    return $events;  
    
     

}
