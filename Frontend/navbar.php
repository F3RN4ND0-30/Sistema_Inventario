<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Inventario</a>

        <a href="dashboard.php" class="btn btn-nav btn-nav-light ms-3">Volver al Inicio</a>
        <a href="detalles_dispositivo.php" class="btn btn-nav btn-nav-primary ms-2">Ver detalles dispositivo</a>

        <div class="d-flex ms-auto align-items-center">
            <span class="navbar-text text-white me-3">
                Hola, <?= isset($_SESSION["usuario"]) ? htmlspecialchars($_SESSION["usuario"]) : 'Invitado' ?>
            </span>
            <a href="logout.php" class="btn btn-nav btn-nav-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>