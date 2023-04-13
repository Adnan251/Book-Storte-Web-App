<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'vendor/autoload.php';

    Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=mzdb','root',''));

    Flight::route('/', function(){
        echo 'Hello World!';
    });

    Flight::route('GET /api/users', function(){
        $users = Flight::db()->query("SELECT * FROM users", PDO::FETCH_ASSOC) -> fetchAll();
        var_dump($users);
        Flight::json($users);
    });

    Flight::start();
?>