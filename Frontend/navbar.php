<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light navbar-pastel-azul">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand fw-bold text-primary" href="#">Inventario</a>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="dashboard.php" class="btn btn-nav-light">🏠 Inicio</a>
            <a href="detalles_dispositivo.php" class="btn btn-nav-primary">📋 Detalles</a>
        </div>

        <div class="d-flex align-items-center ms-auto gap-3">
            <span class="navbar-text text-dark fw-semibold">
                👤 <?= isset($_SESSION["usuario"]) ? htmlspecialchars($_SESSION["usuario"]) : 'Invitado' ?>
            </span>
            <a href="logout.php" class="btn btn-nav-outline-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>