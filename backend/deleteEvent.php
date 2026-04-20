<?php
require_once 'bd.php';
session_start();

$event_name = $_POST["event_name"];
//echo "<script>console.log(".json_encode("$event_name").");</script>";
if (empty($event_name)){
    exit();
}
$connexion = ConnexionBD::getInstance();
try {
    $request = $connexion->prepare("Delete From events where title = ?");
    $request->execute([$event_name]);
    echo json_encode([
        "success" => true,
        "message" => "Event successfully deleted "    
    ]);
    header("location:../pages/admin.php");
}
catch (PDOException $e){
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()    
    ]);
}

