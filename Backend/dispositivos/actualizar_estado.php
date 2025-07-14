<?php
require_once '../db/db.php'; // Conexión a la base de datos

// Forzar respuesta en JSON
header('Content-Type: application/json; charset=utf-8');

// Validar tipo de petición
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

// Obtener y sanitizar los datos del POST
$idDispositivo = isset($_POST['idDispositivo']) ? intval($_POST['idDispositivo']) : null;
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
$observacion = isset($_POST['observacion']) ? trim($_POST['observacion']) : null;

// Validar datos requeridos
if (!$idDispositivo || !$estado) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

// Preparar y ejecutar la consulta de actualización
$stmt = $conn->prepare("UPDATE tb_dispositivos SET Estado = ?, Observaciones = ? WHERE IdDispositivo = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta']);
    exit;
}

$stmt->bind_param("ssi", $estado, $observacion, $idDispositivo);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Estado y observación actualizados correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la actualización']);
}

$stmt->close();
$conn->close();
