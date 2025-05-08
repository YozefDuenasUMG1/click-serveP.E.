<?php
require_once '../config.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

header('Content-Type: application/json');

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception('Error de conexión: ' . $conn->connect_error);
    }

    $result = $conn->query("SELECT numero_factura, fecha, hora, subtotal, iva, total, cliente FROM facturas ORDER BY created_at DESC");

    if (!$result) {
        throw new Exception('Error al obtener las facturas: ' . $conn->error);
    }

    $facturas = [];
    while ($row = $result->fetch_assoc()) {
        $facturas[] = $row;
    }

    echo json_encode(['success' => true, 'facturas' => $facturas]);

    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>