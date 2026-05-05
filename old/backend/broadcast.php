<?php 
require_once 'bd.php';
session_start();

ini_set("SMTP","localhost");
ini_set("smtp_port","1025");

$DomainName = "localhost";
$receivers = $_POST["receivers"];
$subject = $_POST["subject"] === "(no subject)" ? "" : $_POST["subject"];
$body = $_POST["message"];

try {
    if (empty($body) || empty($receivers)){
        echo json_encode([
            "success" => false,
            "message" => "Missing data"
        ]);
        exit();
    }
    $connexion = ConnexionBD::getInstance(); 
    
    $user_id = $_SESSION["id"];
    $request = $connexion->prepare("select club_id from memberships where user_id = ? and role <> 'admin' ");
    $request->execute([$user_id]); 
    $club_id = $request->fetch(PDO::FETCH_ASSOC)["club_id"];
    
    switch ($receivers) {
        case 'all':
            $request = $connexion->prepare("select e.email from memberships m join etudiant e on e.id = m.user_id where club_id = ? and m.role <> 'admin'"); 
            $request->execute([$club_id]);
            $emails = $request->fetchAll(PDO::FETCH_ASSOC);
            $emails = array_column($emails,"email");
            $emails = array_filter($emails,function($email){
                    return filter_var($email,FILTER_VALIDATE_EMAIL);
            });
            break;
        default:
            break;
    } 


    $headers = "From: no-reply@" .$DomainName. "\r\n";
    $headers = "Reply-To:".$_SESSION["user_email"]."\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $email_string = implode(", ",$emails);
    mail($email_string,$subject,$body,$headers); 

    echo json_encode([
        "success" => true,
        "message" => "E-mail sent successfully"
    ]);
}
catch(PDOException $pdoe){
    echo json_encode([
        "success" => false,
        "message" => $pdoe->getMessage()
    ]);
}
catch(Exception $e){
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
