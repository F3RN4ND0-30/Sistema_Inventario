<?php

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

$idDispositivo      = $data['IdDispositivo'];
$tamano_pantalla    = $data['Tamaño_Pantalla'] ?? '';
$almacenamiento     = $data['Almacenamiento'] ?? '';
$sistema_operativo  = $data['Sistema_Operativo'] ?? '';

$sql = "INSERT INTO tb_celular (IdDispositivo, Tamaño_Pantalla, Almacenamiento, Sistema_Operativo) 
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparación de la consulta: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("isss", $idDispositivo, $tamano_pantalla, $almacenamiento, $sistema_operativo);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
