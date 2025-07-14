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

$idDispositivo    = $data['IdDispositivo'];
$red              = $data['Red'] ?? '';
$cantidad_discos  = $data['Cantidad_Discos'] ?? '';
$ups              = $data['UPS'] ?? '';
$sistema_operativo= $data['Sistema_Operativo'] ?? '';

$sql = "INSERT INTO tb_servidor (IdDispositivo, Red, Cantidad_Discos, UPS, Sistema_Operativo) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparación de la consulta: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("issss", $idDispositivo, $red, $cantidad_discos, $ups, $sistema_operativo);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
