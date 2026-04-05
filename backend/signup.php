<?php
require_once 'bd.php';
session_start();

$fname = $_POST["firstName"] ?? '';
$lname = $_POST["lastName"] ?? '';
$phone = $_POST["phone"] ?? '';
$email = $_POST["email"] ?? '';
$psswd = $_POST["password"] ?? '';

if ($email === '' || $psswd === '') {
    echo 'missing credentials';
    exit;
}

$conn = ConnexionBD::getInstance();
$req = $conn->prepare("SELECT * FROM etudiant WHERE email = :email");
$req->execute(array('email' => $email));
$result = $req->fetch(PDO::FETCH_ASSOC);

if ($result) {

    $_SESSION["error"] = "email already exists";
    header("Location: ../pages/signup.php");
    exit;

}

$req = $conn->prepare("INSERT INTO etudiant(last_name, first_name, email, phone, password) VALUES(:nom, :prenom, :email, :phone, :password)");
$req->execute(array('nom' => $lname, 'prenom' => $fname, 'email' => $email, 'phone' => $phone, 'password' => $psswd));

$_SESSION["email"] = $email;
$_SESSION["role"] = "member";
$_SESSION["logged"] = "yes";
$_SESSION["user_email"] = $email;
$_SESSION["user_first_name"] = $fname;
$_SESSION["user_last_name"] = $lname;

header("Location: ../index.php");
exit;
?>