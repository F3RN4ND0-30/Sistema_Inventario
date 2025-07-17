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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="../Backend/css/estilos.css" />
</head>

<body class="bg-light">

    <?php include 'navbar.php'; ?>

    <main class="container bg-white rounded shadow-sm p-4" style="max-width: 950px; margin-top: 2rem;">
        <div class="text-center mb-4">
            <h4 class="text-secondary">Buscar Detalles del Dispositivo</h4>
        </div>

        <div class="bg-white p-4 rounded shadow-sm mx-auto" style="max-width: 800px;">
            <form id="formBuscar" class="row g-3" onsubmit="verDetalles(event)">
                <div class="col-md-8">
                    <input
                        type="text"
                        id="inputCodPatrimonial"
                        name="inputCodPatrimonial"
                        class="form-control"
                        placeholder="Ingrese Código Patrimonial"
                        required />
                </div>
                <div class="col-md-4 d-grid">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>

            <div id="detallesDispositivo" class="mt-4 detalles-contenedor"></div>
        </div>
    </main>

    <!-- JS y AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function verDetalles(event) {
            event.preventDefault();

            const codPatrimonial = $('#inputCodPatrimonial').val().trim();
            if (!codPatrimonial) {
                alert("Debe ingresar un código patrimonial");
                return;
            }

            $.ajax({
                url: '../Backend/api/obtener_detalles.php',
                method: 'POST',
                data: {
                    codPatrimonial
                },
                success: function(respuesta) {
                    $('#detallesDispositivo').hide().html(respuesta).fadeIn('slow');
                },
                error: function() {
                    $('#detallesDispositivo').html('<div class="alert alert-danger">Error al obtener los detalles</div>');
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>