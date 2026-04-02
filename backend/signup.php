<?php
    require_once 'bd.php';
    session_start();
    if (isset($_POST["firstName"])){
        $fname = $_POST["firstName"];
        
    }
    if (isset($_POST["lastName"])){
        $lname = $_POST["lastName"];
        
    }
    if (isset($_POST["phone"])){
        $phone = $_POST["phone"];
        
    }
    if (isset($_POST["email"])){
        $email = $_POST["email"];
        
    }
    if (isset($_POST["password"])){
        $psswd = $_POST["password"];
        
    }
    $conn =ConnexionBD::getInstance();
    $req = $conn->prepare("SELECT * FROM etudiant WHERE email=:email");
    $req->execute(array('email' => $email));
    $result = $req->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo 'user already exists';
        header("../index.html");
    } else {
        $req = $conn->prepare("INSERT INTO etudiant(nom,prenom,email,phone,password) VALUES(:nom,:prenom,:email,:phone,:password)");
        $req->execute(array('nom'=>$lname,'prenom'=>$fname,'email'=>$email,"phone"=>$phone,"password"=>$psswd));
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $psswd;
        header("../index.html");
    }

   
?>