<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../db/db.php';

header('Content-Type: application/json');

$areas = [];
$query = "SELECT IdArea, descripcion FROM tb_areas";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    exit;
}

while ($row = $result->fetch_assoc()) {
    $areas[] = $row;
}

echo json_encode($areas);
