<?php
// Mostrar errores para debug (quítalo en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar conexión a la base de datos de forma segura
require_once '../db/db.php';


// Establecer el tipo de contenido a JSON
header('Content-Type: application/json');

$tipos = [];

$query = "SELECT IdTipoDispositivo, NombreTipo FROM tb_tipodispositivo";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    exit;
}

while ($row = $result->fetch_assoc()) {
    $tipos[] = $row;
}

echo json_encode($tipos);
?>