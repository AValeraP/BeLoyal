<?php
// test_login.php
require_once 'conn/conexion.php';

$email = 'alejandro@peluqueria.com';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

var_dump($usuario);

echo "<br>¿password_verify funciona? ";
var_dump(password_verify('password', $usuario['password'] ?? ''));
?>