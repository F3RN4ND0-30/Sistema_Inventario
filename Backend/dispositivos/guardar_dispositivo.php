<?php
header('Content-Type: application/json');

include '../db/db.php';

$data = json_decode(file_get_contents("php://input"), true);

// Usar los mismos nombres que en el JSON enviado
$codigo = $data['codigo_patrimonial'] ?? null;
$area = $data['id_area'] ?? null;
$estado = $data['estado'] ?? null;
$observacion = $data['observaciones'] ?? null;
$idTipo = $data['tipo_dispositivo'] ?? null; // Este ya es ID directo

// Validar
if (!$codigo || !$area || !$estado || !$idTipo) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

// Insertar en la tabla tb_dispositivos
$sql = "INSERT INTO tb_dispositivos (CodigoPatrimonial, IdTipoDispositivo, IdArea, Estado, FechaRegistro, Observaciones)
        VALUES (?, ?, ?, ?, NOW(), ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siiss", $codigo, $idTipo, $area, $estado, $observacion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar dispositivo']);
}

$conn->close();
?>

