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
    
    <!-- Enlazamos el archivo CSS -->
    <link rel="stylesheet" href="/SISTEMA_INVENTARIO/Backend/css/estilos.css">
    <style>
        /* Fondo suave y altura mínima para que ocupe toda la pantalla */
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
        }

        /* Contenedor centrado y con padding */
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Ajustar el área del formulario */
        #formBuscar {
            max-width: 700px;
            margin: 0 auto;
        }

        /* Espacio entre el navbar y el contenido */
        main {
            padding-top: 1.5rem;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <main class="container bg-white rounded shadow-sm">
        <h1 class="mb-4 text-center">Buscar Detalles del Dispositivo</h1>

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

        <!-- Div para cargar resultados, con clase para aplicar estilos -->
        <div id="detallesDispositivo" class="mt-4 detalles-contenedor"></div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function verDetalles(event) {
            event.preventDefault(); // Evita recarga

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
                    $('#detallesDispositivo').html(respuesta);
                },
                error: function() {
                    alert("Error al obtener los detalles");
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>