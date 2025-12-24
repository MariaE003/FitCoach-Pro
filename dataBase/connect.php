<?php
class DataBase{
    private static $host="localhost";
    private static $dbname="fitcoachpro";
    private  static $username="root";
    private static $pass="";
    private static $pdo = null;

    public static function connect(){

        try{
            if (self::$pdo === null) {
            self::$pdo=new PDO("mysql:host=" .self::$host. ";dbname=" . self::$dbname, self::$username, self::$pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            // echo "done";
            return self::$pdo;
            }
            return self::$pdo;//pour eviter erreur du pdo null
        }catch(PDOException $e){
            die("connection faild:".$e->getMessage());
        }
    }
}
// echo "anan database";




?>