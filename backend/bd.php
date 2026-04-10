<?php
class ConnexionBD {
    private static $_db_name = "insat_clubs";
    private static $_user = "root";
    private static $_psswd = "admin123";
    private static $_bdd;

    private function __construct(){
        try {
            self::$_bdd = new PDO("mysql:host=127.0.0.1;dbname=".self::$_db_name, self::$_user, self::$_psswd, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            
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
