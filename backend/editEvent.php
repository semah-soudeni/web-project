<?php 
require_once 'bd.php';
header("Content-Type: application/json");

$event_id = $_POST["event_id"] ?? null;
if (empty($event_id)) {
    echo json_encode([
        'success' => false,
        'message' => "Event ID is required."
    ]);
    exit();
}

$event_type     = $_POST["Event_Type"];
$description    = $_POST["description"];
$title          = $_POST["title"];
$date           = $_POST["date"];
$time           = $_POST["time"];
$duration       = $_POST["duration"];
$prize          = $_POST["prize"] ?? '';
$location       = $_POST["location"];
$max_attendees  = empty($_POST["max_attendees"]) ? null : $_POST["max_attendees"];
$other_participating_clubs = json_decode($_POST["other_participating_clubs"]);
$STAFF          = array_values($_POST["staffmember"]);
$photos         = $_FILES["staffmember"] ?? null;
if (empty($event_type) || empty($description) || empty($title) || empty($date) || empty($time) || empty($duration)
    || empty($location) || empty($STAFF)) {
    echo json_encode([
        'success' => false,
        'message' => "Data is missing. Please check again.",
        'event_type'  => json_encode($event_type),
        'description' => json_encode($description),
        'title'       => json_encode($title),
        'date'        => json_encode($date),
        'time'        => json_encode($time),
        'duration'    => json_encode($duration),
        'location'    => json_encode($location),
        'staff'       => json_encode($STAFF),
        'other_participating_clubs' => json_encode("$other_participating_clubs")
    ]);
    exit();
}

try {
    $connexion = ConnexionBD::getInstance();

    // Check event exists
    $check_event = $connexion->prepare("SELECT id FROM events WHERE id = ?");
    $check_event->execute([$event_id]);
    if (!$check_event->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'success' => false,
            'message' => "Event not found."
        ]);
        exit();
    }

    // Validate staff emails and collect user IDs
    $users_ids = [];
    foreach ($STAFF as $key => $value) {
        if (empty($value["email"]) || empty($value["role"])) {
            echo json_encode([
                'success' => false,
                'message' => "Data is missing. Please check again."
            ]);
            exit();
        }
        $check_email = $connexion->prepare("SELECT id FROM etudiant WHERE email = ?");
        $check_email->execute([$value["email"]]);
        $user = $check_email->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            echo json_encode([
                "success" => false,
                "message" => $value["role"] . " email does not exist, please make sure staff members have accounts",
                "email"   => $value["email"],
            ]);
            exit();
        } else {
            $users_ids[$key] = $user["id"];
        }
    }

    // Update event
    $request = $connexion->prepare("
        UPDATE events 
        SET title=?, description=?, event_date=?, event_time=?, location=?, 
            max_attendees=?, duration=?, event_type=?, prize_pool=?
        WHERE id=?
    ");
    $request->execute([
        $title,
        $description,
        $date,
        $time,
        $location,
        $max_attendees,
        $duration,
        $event_type,
        $prize,
        $event_id
    ]);

    // Replace participating clubs
    $delete_clubs = $connexion->prepare("DELETE FROM club_events WHERE event_id = ?");
    $delete_clubs->execute([$event_id]);

    foreach ($other_participating_clubs as $value) {
        $club_ids = $connexion->prepare("SELECT id FROM clubs WHERE name = ?");
        $club_ids->execute([$value]);
        $club = $club_ids->fetch(PDO::FETCH_ASSOC);
        if ($club) {
            $club_event = $connexion->prepare("INSERT INTO club_events(club_id, event_id) VALUES(?, ?)");
            $club_event->execute([$club["id"], $event_id]);
        }
    }

    // Replace staff
    // Fetch old photos before deleting so we can clean up files
    $old_photos = $connexion->prepare("SELECT photo FROM staff WHERE event_id = ?");
    $old_photos->execute([$event_id]);
    $old_photo_paths = $old_photos->fetchAll(PDO::FETCH_COLUMN);

    $delete_staff = $connexion->prepare("DELETE FROM staff WHERE event_id = ?");
    $delete_staff->execute([$event_id]);

    foreach ($users_ids as $key => $value) {
        $photo_path = null;

        $fileTmp  = $photos["tmp_name"][$key]["photo"] ?? null;
        $fileName = $photos["name"][$key]["photo"] ?? null;

        if ($fileTmp && is_uploaded_file($fileTmp)) {

            $newFileName = uniqid() . "_" . basename($fileName);
            $destination = $_SERVER["DOCUMENT_ROOT"] . "/uploaded/" . $newFileName;
            move_uploaded_file($fileTmp, $destination);
            $photo_path = "/uploaded/" . $newFileName;
        } else if ($_POST["staffmember"][$key]["deletePhoto"]) {
            $photo_path = null;
                
        } else {
            $photo_path = $_POST["staffmember"][$key]["existingPhoto"] ?? null;
        }

        $staffInsertion = $connexion->prepare("INSERT INTO staff(user_id, photo, role, event_id) VALUES (?, ?, ?, ?)");
        $staffInsertion->execute([
            $value,
            $photo_path,
            $STAFF[$key]["role"],
            $event_id
        ]);
    }

    // Delete old photo files that are no longer referenced
    $new_photos = $connexion->prepare("SELECT photo FROM staff WHERE event_id = ?");
    $new_photos->execute([$event_id]);
    $new_photo_paths = $new_photos->fetchAll(PDO::FETCH_COLUMN);

    foreach ($old_photo_paths as $old_path) {
        if ($old_path && !in_array($old_path, $new_photo_paths)) {
            $full_path = $_SERVER["DOCUMENT_ROOT"] . $old_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }
    }

    echo json_encode([
        "success" => true,
        "message" => "Event has been updated successfully",
        "event_id" => $event_id,
        "other_p_c" => json_encode($other_participating_clubs),
        "staff" => $STAFF
    ]);

} catch (PDOException $exception) {
    $mysqlCode = $exception->errorInfo[1];
    if ($mysqlCode == 1062) {
        echo json_encode([
            "success" => false,
            "message" => "This event already exists"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => $exception->getMessage()
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
