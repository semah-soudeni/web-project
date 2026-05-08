<?php
    require_once __DIR__ . '/../includes/init.php';
    require_once 'bd.php';
    session_start();
    $conn = ConnexionBD::getInstance();

    if(!isset($_SESSION["id"])){
      header("LOCATION: ".BASE_URL."pages/signin.php");
      exit();
    }
    $selectedClub = $_POST["club"];
    $selectedRole = $_POST["role"];

    $req = $conn->prepare("INSERT INTO memberships(user_id,club_id,role) VALUES(:user_id,:club_id,:role)");
    $req->execute(array('user_id'=>$_SESSION["id"],'club_id'=>$selectedClub,"role"=>$selectedRole));
    header("LOCATION:.. index.php");
?>
