<?php
require_once '../../config.php'; // Ruta corregida para incluir config.php desde la raíz del proyecto

// Código existente para probar la conexión
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    echo "Conexión exitosa a la base de datos.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
