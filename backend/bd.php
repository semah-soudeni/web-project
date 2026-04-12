<?php
class ConnexionBD {
    private static $_db_name = "insat_clubs";
    private static $_user = "root";
    private static $_psswd = "admin123";
    private static $_bdd;

    private function __construct(){
        try {
            self::$_bdd = new PDO("mysql:host=127.0.0.1;dbname=".self::$_db_name.";charset=utf8mb4", self::$_user, self::$_psswd);
            
        }
        catch (PDOException $e) {
            die("erreur".$e->getMessage());
        }
    }
    public static function getInstance(){
        if(self::$_bdd === null){
            new ConnexionBD();
        }
        return self::$_bdd;
    }
}

?>
