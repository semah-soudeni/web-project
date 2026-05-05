<?php

    session_start();
    require_once 'bd.php';

    $conn = ConnexionBD::getInstance();

    
   if(isset($_SESSION["logged"]) && $_SESSION["logged"] == "yes"){

        $stmt = $conn->prepare(
            "INSERT INTO register VALUES(NULL,:user_id,:event_id,0"
        );
       }
    else{
       header("Location: signin.php");

   }
