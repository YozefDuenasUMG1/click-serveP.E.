<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes para la conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', ''); // Contraseña vacía cuando no hay contraseña
define('DB_NAME', 'pedidos');

// Establecer conexión mysqli
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
$conn = $conexion;

// Establecer conexión PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASSWORD,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>