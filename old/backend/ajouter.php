<?php
    require_once 'bd.php';
    session_start();
    $conn = ConnexionBD::getInstance();

    $selectedClub = $_POST["club"];
    $selectedRole = $_POST["role"];

    $req = $conn->prepare("INSERT INTO memberships(user_id,club_id,role) VALUES(:user_id,:club_id,:role)");
    $req->execute(array('user_id'=>$_SESSION["id"],'club_id'=>$selectedClub,"role"=>$selectedRole));
    header("LOCATION:.. index.php");
?>