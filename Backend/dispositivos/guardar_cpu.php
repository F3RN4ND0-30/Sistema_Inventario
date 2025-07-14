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

$idDispositivo = $data['IdDispositivo'];
$procesador   = $data['Procesador'] ?? '';
$ram          = $data['RAM'] ?? '';
$disco        = $data['Disco'] ?? '';
$video        = $data['Video'] ?? '';
$placa        = $data['Placa'] ?? '';

$sql = "INSERT INTO tb_cpu (IdDispositivo, Procesador, RAM, Disco, Video, Placa) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparación de la consulta: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("isssss", $idDispositivo, $procesador, $ram, $disco, $video, $placa);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
