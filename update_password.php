<?php
require_once __DIR__ . '/config.php';

$usuario = 'admin'; // Cambia esto al usuario que deseas actualizar
$nueva_contraseña = 'admin123'; // Cambia esto a la nueva contraseña
$hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

$sql = "UPDATE usuarios SET password = ? WHERE usuario = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la consulta SQL: " . $conn->error);
}
$stmt->bind_param("ss", $hash, $usuario);
if ($stmt->execute()) {
    echo "Contraseña actualizada correctamente.";
} else {
    echo "Error al actualizar la contraseña: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>