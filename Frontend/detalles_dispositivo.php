<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalles del Dispositivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-4">
    <h1 class="mb-4">Buscar Detalles del Dispositivo</h1>

    <form id="formBuscar" class="row g-3">
        <div class="col-md-6">
            <input
                type="text"
                id="codigo"
                name="codigo"
                class="form-control"
                placeholder="Ingrese Código Patrimonial"
                required
            />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <div id="resultado" class="mt-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/detalles.js"></script>
</body>
</html>
