<?php
// Backend/dispositivos/guardar_variado.php

require_once '../db/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['IdDispositivo'])) {
    echo json_encode([
        "success" => false,
        "message" => "Falta el IdDispositivo."
    ]);
    exit;
}

$idDispositivo = $data['IdDispositivo'];
$modelo       = $data['Modelo'] ?? '';
$marca        = $data['Marca'] ?? '';

$sql = "INSERT INTO tb_variado (IdDispositivo, Modelo, Marca) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparación de la consulta: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("iss", $idDispositivo, $modelo, $marca);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();

