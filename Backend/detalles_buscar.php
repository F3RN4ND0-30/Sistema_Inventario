<?php
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_GET['codigo']) || empty(trim($_GET['codigo']))) {
    echo json_encode(['error' => 'Debe ingresar un código patrimonial.']);
    exit;
}

$codigo = trim($_GET['codigo']);

// Buscar dispositivo general + tipo
$stmt = $conn->prepare("SELECT d.*, t.NombreTipo FROM Tb_Dispositivos d
                        JOIN Tb_TipoDispositivo t ON d.IdTipoDispositivo = t.IdTipoDispositivo
                        WHERE d.CodigoPatrimonial = ?");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(['error' => 'No se encontró dispositivo con ese código.']);
    exit;
}

$dispositivo = $resultado->fetch_assoc();
$tipo = $dispositivo['NombreTipo'];
$idDispositivo = $dispositivo['IdDispositivo'];

// Consultar datos especializados según tipo
$datosEspecializados = null;

switch ($tipo) {
    case 'CPU':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_CPU WHERE IdDispositivo = ?");
        break;
    case 'Celular':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_Celular WHERE IdDispositivo = ?");
        break;
    case 'Laptop':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_Laptop WHERE IdDispositivo = ?");
        break;
    case 'Tablet':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_Tablet WHERE IdDispositivo = ?");
        break;
    case 'Servidor':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_Servidor WHERE IdDispositivo = ?");
        break;
    case 'Impresora':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_Impresora WHERE IdDispositivo = ?");
        break;
    case 'Teclado':
        $stmt2 = $conn->prepare("SELECT * FROM Tb_Teclado WHERE IdDispositivo = ?");
        break;
    default:
        $stmt2 = null;
}

if ($stmt2) {
    $stmt2->bind_param("i", $idDispositivo);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $datosEspecializados = $res2->fetch_assoc();
}

echo json_encode([
    'dispositivo' => $dispositivo,
    'datosEspecializados' => $datosEspecializados
]);
