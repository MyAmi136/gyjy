<?php

class Database {

    private static $host = "localhost";
    private static $dbname = "cosmetics_ecommerce";
    private static $username = "root";
    private static $password = "";

    public static function connect(){

        try{
            $conn = new PDO(
                "mysql:host=".self::$host.";dbname=".self::$dbname.";charset=utf8mb4",
                self::$username,
                self::$password,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("SET NAMES utf8mb4");

            return $conn;

        }catch(PDOException $e){
            die("DB Connection failed: ".$e->getMessage());
        }
    }
}