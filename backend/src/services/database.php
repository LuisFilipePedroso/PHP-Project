<?php

function connect() {
    $dsn = 'mysql:host=localhost;dbname=fuckingproject1';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO($dsn, $user, $pass);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error on connect: ' . $e->getMessage();
    }
}