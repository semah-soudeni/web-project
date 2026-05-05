<?php 
require_once 'bd.php';
header("Content-Type: application/json");

$event_type=$_POST["Event_Type"];
$description=$_POST["description"];
$title=$_POST["title"];
$date=$_POST["date"];
$time=$_POST["time"];
$duration=$_POST["duration"];
$prize=$_POST["prize"] ?? '';
$location=$_POST["location"];
$max_attendees= empty($_POST["max_attendees"]) ? null : $_POST["max_attendees"];

$other_participating_clubs=json_decode($_POST["other_participating_clubs"]) ;
$STAFF=array_values($_POST["staffmember"]) ;
$photos = $_FILES["staffmember"] ?? null;

if (empty($event_type) || empty($description) || empty($title) || empty($date) || empty($time) || empty($duration) 
   || empty($location) || empty($STAFF)  ){
   echo json_encode([
       'success' => false,
       'message' => "Data is missing. Please check again. ",
       'event_type' => json_encode($event_type),
       'description' => json_encode($description),
       'title' => json_encode($title),
       'date' => json_encode($date),
       'time' => json_encode($time),
       'duration' => json_encode($duration),
       'location' => json_encode($location),
       'staff' => json_encode($STAFF),
       "other_participating_clubs" => json_encode("$other_participating_clubs")
   ]);
   exit();
}
try {
    $connexion = ConnexionBD::getInstance();

    $users_ids = [];
    foreach ($STAFF as $key => $value) {
        if (empty($value["email"]) || empty($value["role"])){
            echo json_encode([
               'success' => false,
               'message' => "Data is missing. Please check again.",       
            ]);
            exit();
        }
        $check_email = $connexion->prepare("SELECT id FROM etudiant WHERE email = ?");
        $check_email->execute([$value["email"]]);
        $user = $check_email->fetch(PDO::FETCH_ASSOC);

        if (!$user){
            echo json_encode([
                "success" => false,
                "message" => $value["role"] . " email does not exist , please make sure staff members have accounts",
                "email" => $value["email"],
            ]);
            exit();
        }
        else {
        
            $users_ids[$key] = $user["id"];
        }
    }


    $request = $connexion->prepare("INSERT INTO events(title,description,event_date,event_time,location,max_attendees,duration,event_type,prize_pool) VALUES (?,?,?,?,?,?,?,?,?)");
    $request->execute([
        $title,
        $description,
        $date,
        $time,
        $location,
        $max_attendees,
        $duration,
        $event_type,
        $prize
    ]);
    $event = $connexion->lastInsertId();
     
    foreach($other_participating_clubs as $key => $value){
        $club_ids= $connexion->prepare("SELECT id FROM clubs WHERE name = ?");
        $club_ids->execute([$value]);
        $club = $club_ids->fetch(PDO::FETCH_ASSOC);
        $club_event = $connexion->prepare("INSERT INTO club_events(club_id,event_id) VALUES(?,?)");
        $club_event->execute([$club["id"],$event]);
    }

    foreach ($users_ids as $key => $value){
        $fileName = uniqid() . "_" . basename($photos["name"][$key]["photo"]);
        $fileTmp = $photos["tmp_name"][$key]["photo"];

        if ($fileTmp && is_uploaded_file($fileTmp)) {

            $destination = $_SERVER["DOCUMENT_ROOT"] . "/uploaded/" . $fileName;

            move_uploaded_file($fileTmp, $destination);
        }
        $relative_path = "/uploaded/" . $fileName;
        
        $staffInsertion = $connexion->prepare("INSERT INTO staff(user_id,photo,role,event_id) VALUES (?,?,?,?)");
        $staffInsertion->execute([
            $value,
            $relative_path,
            $STAFF[$key]["role"],
            $event
        ]);
    }
    echo json_encode([
        "success" => true,
        "message" => "Event has been added successufully",
        "other p_c" => json_encode($other_participating_clubs),
        "files" => $_FILES,
        "staff" => $STAFF
    ]);
}
catch (PDOException $exception){
    $mysqlCode = $exception->errorInfo[1];
    if ($mysqlCode == 1062){
        echo json_encode([
            "success" => false,
            "message" => "This event already exists"
        ]);
    }
    else {
        echo json_encode([
            "success" => false,
            "message" => $exception->getMessage(),
        ]);
    }
}
catch (Exception $e){
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()]);
}


