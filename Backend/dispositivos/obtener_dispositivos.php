<?php
require_once __DIR__ . '/../db/db.php';

header('Content-Type: application/json');

// Tu consulta SQL
$query = "SELECT d.IdDispositivo, d.CodigoPatrimonial, td.NombreTipo, a.descripcion, d.Estado, d.FechaRegistro, d.Observaciones
          FROM tb_dispositivos d
          JOIN Tb_Areas a ON d.IdArea = a.IdArea
          JOIN tb_TipoDispositivo td ON d.IdTipoDispositivo = td.IdTipoDispositivo
          ORDER BY d.IdDispositivo DESC";

$result = $conn->query($query);

$dispositivos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dispositivos[] = $row;
    }
}

echo json_encode($dispositivos);
