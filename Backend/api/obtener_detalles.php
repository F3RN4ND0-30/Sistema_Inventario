<?php
require_once '../db/db.php';

$cod = $_POST['codPatrimonial'] ?? '';

if (!$cod) {
    exit("<div class='alert alert-warning text-center'>Código patrimonial no recibido.</div>");
}

// Buscar dispositivo
$query = "SELECT * FROM tb_dispositivos WHERE CodigoPatrimonial = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $cod);
$stmt->execute();
$result = $stmt->get_result();
$dispositivo = $result->fetch_assoc();

if (!$dispositivo) {
    exit("<div class='alert alert-danger text-center'>No se encontró el dispositivo.</div>");
}

$id = $dispositivo['IdDispositivo'];

// Reemplazar IdArea por su descripción
if (isset($dispositivo['IdArea'])) {
    $areaQuery = $conn->prepare("SELECT descripcion FROM tb_areas WHERE IdArea = ?");
    $areaQuery->bind_param("i", $dispositivo['IdArea']);
    $areaQuery->execute();
    $areaResult = $areaQuery->get_result();
    if ($areaData = $areaResult->fetch_assoc()) {
        $dispositivo['Área'] = $areaData['descripcion'];
    }
    unset($dispositivo['IdArea']);
}

// Reemplazar IdTipoDispositivo por su nombre
if (isset($dispositivo['IdTipoDispositivo'])) {
    $tipoQuery = $conn->prepare("SELECT NombreTipo FROM tb_tipodispositivo WHERE IdTipoDispositivo = ?");
    $tipoQuery->bind_param("i", $dispositivo['IdTipoDispositivo']);
    $tipoQuery->execute();
    $tipoResult = $tipoQuery->get_result();
    if ($tipoData = $tipoResult->fetch_assoc()) {
        $dispositivo['Tipo de Dispositivo'] = $tipoData['NombreTipo'];
    }
    unset($dispositivo['IdTipoDispositivo']);
}

// Buscar en tablas específicas
$tablas = ['tb_cpu', 'tb_laptop', 'tb_tablet', 'tb_celular', 'tb_servidor', 'tb_variado'];
$detalles = null;

foreach ($tablas as $tabla) {
    $q = "SELECT * FROM $tabla WHERE IdDispositivo = ?";
    $s = $conn->prepare($q);
    $s->bind_param("i", $id);
    $s->execute();
    $r = $s->get_result();
    $fila = $r->fetch_assoc();
    if ($fila) {
        $detalles = $fila;
        break;
    }
}

if (!$detalles) {
    exit("<div class='alert alert-info text-center'>No se encontraron detalles adicionales para el dispositivo.</div>");
}

// Mostrar tabla con clases para estilos en estilos.css
echo "
<div class='detalles-contenedor'>
<table class='table table-bordered custom-table'>
    <colgroup>
        <col style='width: 280px;'>
        <col style='width: calc(100% - 280px);'>
    </colgroup>
    <thead>
        <tr><th>Campo</th><th>Valor</th></tr>
    </thead>
    <tbody>
";

// Mostrar datos generales
foreach ($dispositivo as $campo => $valor) {
    echo "<tr><td><strong>" . htmlspecialchars($campo) . "</strong></td><td>" . htmlspecialchars($valor) . "</td></tr>";
}

// Mostrar detalles específicos (sin IdDispositivo)
foreach ($detalles as $campo => $valor) {
    if ($campo === 'IdDispositivo') continue;
    echo "<tr><td><strong>" . htmlspecialchars($campo) . "</strong></td><td>" . htmlspecialchars($valor) . "</td></tr>";
}

echo "
    </tbody>
</table>
</div>";
