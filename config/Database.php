<?php
class Database{
    private static $conn;

    public static function conectar(){
        if(!self::$conn){
            self::$conn = new PDO(
                "pgsql:host=192.168.10.37;port=5432;dbname=crud_jogo",
                "crud_jogo",
                "crud_jogo",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$conn;
    }
}
?>
