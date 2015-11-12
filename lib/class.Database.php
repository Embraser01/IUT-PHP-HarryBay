<?php

require __DIR__ . '/../params/params.php';

class Database {

    private static $pdo;

    public function __construct($host, $user, $password, $dbname) {
        try {
            self::$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $password, [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);

            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            var_dump($e->getMessage());
        }

        return self::$pdo;
    }

    public function get() {
        return self::$pdo;
    }

}

$jeej = new Database($host, $user, $password, $dbName);
$db = $jeej->get();