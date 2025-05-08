<?php
require_once '../config.php';

// Configurar encabezados para respuesta JSON
header('Content-Type: application/json');

// Log detallado
file_put_contents('../error_log.txt', date('Y-m-d H:i:s') . " - Inicio de procesamiento\n", FILE_APPEND);

try {
    // Verificar método y contenido
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido. Se requiere POST.');
    }

    if (empty($_SERVER['CONTENT_TYPE']) || strpos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
        throw new Exception('Tipo de contenido incorrecto. Se requiere application/json.');
    }

    // Leer y decodificar input
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('No se recibieron datos.');
    }

    file_put_contents('../error_log.txt', date('Y-m-d H:i:s') . " - Datos recibidos: " . $input . "\n", FILE_APPEND);
    
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // Validar campos obligatorios
    $requiredFields = ['numero_factura', 'fecha', 'hora', 'subtotal', 'iva', 'total'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Campo requerido faltante: $field");
        }
    }

    // Asignar valores con validación
    $numero_factura = trim($data['numero_factura']);
    $fecha = trim($data['fecha']);
    $hora = trim($data['hora']);
    $subtotal = filter_var($data['subtotal'], FILTER_VALIDATE_FLOAT);
    $iva = filter_var($data['iva'], FILTER_VALIDATE_FLOAT);
    $total = filter_var($data['total'], FILTER_VALIDATE_FLOAT);
    $cliente = isset($data['cliente']) ? trim($data['cliente']) : null;

    if ($subtotal === false || $iva === false || $total === false) {
        throw new Exception('Valores numéricos inválidos en subtotal, iva o total');
    }

    // Conexión a la base de datos
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $conn->connect_error);
    }

    // Preparar y ejecutar consulta
    $stmt = $conn->prepare("INSERT INTO facturas (numero_factura, fecha, hora, subtotal, iva, total, cliente) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $conn->error);
    }

    $stmt->bind_param('sssddds', $numero_factura, $fecha, $hora, $subtotal, $iva, $total, $cliente);
    
    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }

    // Éxito
    echo json_encode([
        'success' => true,
        'message' => 'Factura guardada correctamente.',
        'id' => $conn->insert_id
    ]);

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    file_put_contents('../error_log.txt', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}