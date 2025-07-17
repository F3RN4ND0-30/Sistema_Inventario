<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light navbar-pastel-azul">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand fw-bold text-primary">Inventario</a>

        <!-- Botón hamburguesa (colapsa contenido en móvil) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable -->
        <div class="collapse navbar-collapse" id="navbarContenido">
            <!-- Botones a la izquierda -->
            <ul class="navbar-nav me-auto gap-2">
                <li class="nav-item">
                    <a href="dashboard.php" class="btn btn-nav-light">🏠 Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="detalles_dispositivo.php" class="btn btn-nav-primary">📋 Detalles</a>
                </li>
            </ul>

            <!-- Usuario y logout a la derecha -->
            <ul class="navbar-nav ms-auto align-items-center gap-2 flex-column flex-lg-row">
                <!-- Usuario -->
                <li class="nav-item">
                    <span class="navbar-text fw-semibold text-dark d-flex align-items-center gap-1">
                        👤 <?= isset($_SESSION["usuario"]) ? htmlspecialchars($_SESSION["usuario"]) : 'Invitado' ?>
                    </span>
                </li>

                <!-- Botón cerrar sesión -->
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-nav-outline-danger btn-sm">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>