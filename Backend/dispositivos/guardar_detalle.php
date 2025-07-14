<?php
header('Content-Type: application/json');
require_once '../db/db.php';

$data = json_decode(file_get_contents('php://input'), true);

$tabla = $data['tabla'] ?? '';
$campos = $data['datos'] ?? [];

if (empty($tabla) || empty($campos)) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

try {
    // Armamos dinámicamente las columnas y los valores
    $columnas = implode(', ', array_keys($campos));
    $placeholders = implode(', ', array_fill(0, count($campos), '?'));
    $valores = array_values($campos);

    $sql = "INSERT INTO $tabla ($columnas) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);

    // Agregar tipos (asumimos que todos son strings por simplicidad)
    $tipos = str_repeat('s', count($valores)); // todos como string

    $stmt->bind_param($tipos, ...$valores);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Detalle insertado correctamente']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al insertar: ' . $e->getMessage()]);
}
