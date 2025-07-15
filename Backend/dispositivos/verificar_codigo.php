<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Ajusta esta ruta según tu estructura real
include '../db/db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $codigo = trim($data['codigo_patrimonial'] ?? '');

    if ($codigo === '') {
        echo json_encode(['existe' => false]);
        exit;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tb_dispositivos WHERE CodigoPatrimonial = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode(['existe' => ($row['total'] > 0)]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}

$conn->close();
