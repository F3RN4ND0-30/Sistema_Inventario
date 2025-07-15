<?php
session_start();
require_once '../Backend/db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["username"] ?? '';
    $clave = $_POST["password"] ?? '';

    $stmt = $conn->prepare("SELECT IdUsuario, Clave, Rol, Estado FROM Tb_Usuarios WHERE Usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $rol, $estado);
        $stmt->fetch();

        if ($estado === 'Activo') {
            if (password_verify($clave, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["usuario"] = $usuario;
                $_SESSION["rol"] = $rol;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El usuario está inactivo.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Login - Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/SISTEMA_INVENTARIO/Backend/css/estilos.css" />
</head>

<body class="login-page d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <div class="login-header mb-4">
            <h1 class="titulo-institucional">Sistema de Inventario del Área de Sistemas</h1>
        </div>

        <div class="login-container">
            <div class="login-image" role="img" aria-label="Escudo de Pisco"></div>

            <div class="login-form">
                <h3>Iniciar Sesión</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="login.php" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required />
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>

                <div class="mt-3 text-center">
                    <small>¿No tienes una cuenta? <a href="register.php">Regístrate</a></small>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>