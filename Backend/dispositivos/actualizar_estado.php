<?php
require_once '../db/db.php'; // Ruta correcta para la conexión a la base de datos

// Verificamos si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDispositivo = $_POST['idDispositivo']; // Obtenemos el ID del dispositivo
    $estado = $_POST['estado']; // Obtenemos el nuevo estado

    // Comprobamos si ambos valores son válidos
    if (isset($idDispositivo) && isset($estado)) {
        
        // Preparamos la consulta SQL para actualizar el estado del dispositivo
        $stmt = $conn->prepare("UPDATE tb_dispositivos SET Estado = ? WHERE IdDispositivo = ?");
        $stmt->bind_param("si", $estado, $idDispositivo); // 's' para el estado y 'i' para el ID

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Estado actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el estado']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
