<?php

$host = '127.0.0.1';
$port = 3307;
$db   = 'be_loyal';
$user = 'root';
$pass = '';

try {
$pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=be_loyal;charset=utf8", $user, $pass);    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>