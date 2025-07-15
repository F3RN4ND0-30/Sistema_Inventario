<?php
header('Content-Type: application/json');
include '../db/db.php';

$conn->begin_transaction();

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $codigo = $data['codigo_patrimonial'] ?? null;
    $area = $data['id_area'] ?? null;
    $estado = $data['estado'] ?? null;
    $observacion = $data['observaciones'] ?? null;
    $idTipo = $data['tipo_dispositivo'] ?? null;

    if (!$codigo || !$area || !$estado || !$idTipo) {
        throw new Exception('Faltan datos requeridos');
    }

    $stmt = $conn->prepare("INSERT INTO tb_dispositivos (CodigoPatrimonial, IdTipoDispositivo, IdArea, Estado, FechaRegistro, Observaciones) 
                            VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("siiss", $codigo, $idTipo, $area, $estado, $observacion);
    if (!$stmt->execute()) {
        throw new Exception('Error al guardar dispositivo');
    }

    $nuevoID = $conn->insert_id;

    // Si todo está bien
    $conn->commit();
    echo json_encode(['success' => true, 'id' => $nuevoID]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
