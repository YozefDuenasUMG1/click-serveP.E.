<?php
require_once __DIR__ . '/../../config.php'; // Ruta corregida para incluir config.php

// Reemplazar variables no definidas con constantes de config.php
$servername = DB_HOST;
$dbname = DB_NAME;
$username = DB_USER;
$password = DB_PASSWORD;

// Limpiar cualquier salida previa
ob_clean();

// Establecer headers primero
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Validar que la conexión funciona
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener parámetros de manera segura
    $fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING) ?: date('Y-m-d');
    $mesa = filter_input(INPUT_GET, 'mesa', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_GET, 'estado', FILTER_SANITIZE_STRING);
    
    // Validación estricta de fecha
    if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
        throw new Exception("Formato de fecha inválido. Use YYYY-MM-DD");
    }

    // Consulta SQL con parámetros seguros
    $sql = "SELECT * FROM registropedidos WHERE DATE(fecha_hora_pedido) = :fecha";
    $params = [':fecha' => $fecha];
    
    if ($mesa && $mesa !== 'todas') {
        $sql .= " AND mesa = :mesa";
        $params[':mesa'] = $mesa;
    }
    
    if ($estado && $estado !== 'todos') {
        $sql .= " AND estado = :estado";
        $params[':estado'] = $estado;
    }
    
    $sql .= " ORDER BY fecha_hora_pedido DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular totales
    $total = array_reduce($pedidos, function($sum, $pedido) {
        return $sum + (float)$pedido['total'];
    }, 0);
    
    // Enviar respuesta JSON válida
    die(json_encode([
        'success' => true,
        'pedidos' => $pedidos,
        'total_pedidos' => count($pedidos),
        'total_importe' => number_format($total, 2),
        'fecha_consulta' => $fecha
    ]));
    
} catch(PDOException $e) {
    // Error específico de base de datos
    die(json_encode([
        'success' => false,
        'error' => 'Error de base de datos',
        'message' => $e->getMessage()
    ]));
} catch(Exception $e) {
    // Otros errores
    die(json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]));
}
?>