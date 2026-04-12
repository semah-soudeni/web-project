<?php
require_once 'bd.php';
session_start();

$email = $_POST["email"] ?? '';
$psswd = $_POST["password"] ?? '';

if ($email === '' || $psswd === '') {
    $_SESSION["error"] = 'missing credentials';
    header("Location: ../pages/signin.php");
    exit;
}
$conn = ConnexionBD::getInstance();
$req = $conn->prepare("SELECT * FROM etudiant WHERE email = :email");
$req->execute(array('email' => $email));
$result = $req->fetch(PDO::FETCH_ASSOC);

if (!$result) {

    $_SESSION["error"] = "user doesn't exists";
    header("Location: ../pages/signin.php");
    exit;
} elseif ($result["password"] != $psswd) {
    $_SESSION["error"] = "wrong password";
    header("Location: ../pages/signin.php");
    exit;
} else {
    $_SESSION["id"] = $result["id"];
    $_SESSION["logged"] = "yes";
    $_SESSION["user_email"] = $result["email"];
    $_SESSION["user_first_name"] = $result["first_name"] ?? '';
    $_SESSION["user_last_name"] = $result["last_name"] ?? '';
    $_SESSION['role'] = $result["role"];
    header("Location:../index.php");
}
