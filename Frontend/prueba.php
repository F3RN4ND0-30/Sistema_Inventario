<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tabla con DataTables</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="p-4">

    <h5 class="mb-3">Filtro:</h5>
    <input type="text" id="filtroCodigo" class="form-control mb-4" placeholder="Buscar por código, tipo, etc.">

    <table id="tabla-dispositivos" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Área</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- 18 filas de prueba -->
            <tr>
                <td>1</td>
                <td>CP-001</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-01</td>
                <td>OK</td>
            </tr>
            <tr>
                <td>2</td>
                <td>CP-002</td>
                <td>Impresora</td>
                <td>TI</td>
                <td>Baja</td>
                <td>2024-01-02</td>
                <td>Antigua</td>
            </tr>
            <tr>
                <td>3</td>
                <td>CP-003</td>
                <td>Laptop</td>
                <td>Contabilidad</td>
                <td>Operativo</td>
                <td>2024-01-03</td>
                <td>Nueva</td>
            </tr>
            <tr>
                <td>4</td>
                <td>CP-004</td>
                <td>Celular</td>
                <td>RRHH</td>
                <td>EnReparacion</td>
                <td>2024-01-04</td>
                <td>Batería</td>
            </tr>
            <tr>
                <td>5</td>
                <td>CP-005</td>
                <td>Impresora</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-05</td>
                <td>OK</td>
            </tr>
            <tr>
                <td>6</td>
                <td>CP-006</td>
                <td>Laptop</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-06</td>
                <td>Nuevo</td>
            </tr>
            <tr>
                <td>7</td>
                <td>CP-007</td>
                <td>Celular</td>
                <td>TI</td>
                <td>EnPrestamo</td>
                <td>2024-01-07</td>
                <td>Usuario externo</td>
            </tr>
            <tr>
                <td>8</td>
                <td>CP-008</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-08</td>
                <td>OK</td>
            </tr>
            <tr>
                <td>9</td>
                <td>CP-009</td>
                <td>Laptop</td>
                <td>Contabilidad</td>
                <td>Baja</td>
                <td>2024-01-09</td>
                <td>Antigua</td>
            </tr>
            <tr>
                <td>10</td>
                <td>CP-010</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-10</td>
                <td>OK</td>
            </tr>
            <tr>
                <td>11</td>
                <td>CP-011</td>
                <td>Impresora</td>
                <td>RRHH</td>
                <td>Operativo</td>
                <td>2024-01-11</td>
                <td>Color</td>
            </tr>
            <tr>
                <td>12</td>
                <td>CP-012</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-12</td>
                <td>Listo</td>
            </tr>
            <tr>
                <td>13</td>
                <td>CP-013</td>
                <td>Laptop</td>
                <td>TI</td>
                <td>EnReparacion</td>
                <td>2024-01-13</td>
                <td>Disco dañado</td>
            </tr>
            <tr>
                <td>14</td>
                <td>CP-014</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Baja</td>
                <td>2024-01-14</td>
                <td>Sin IMEI</td>
            </tr>
            <tr>
                <td>15</td>
                <td>CP-015</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-15</td>
                <td>OK</td>
            </tr>
            <tr>
                <td>16</td>
                <td>CP-016</td>
                <td>Impresora</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-16</td>
                <td>Con tóner</td>
            </tr>
            <tr>
                <td>17</td>
                <td>CP-017</td>
                <td>Laptop</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-17</td>
                <td>Recién llegada</td>
            </tr>
            <tr>
                <td>18</td>
                <td>CP-018</td>
                <td>Celular</td>
                <td>TI</td>
                <td>Operativo</td>
                <td>2024-01-18</td>
                <td>Listo</td>
            </tr>
        </tbody>
    </table>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            const tabla = $('#tabla-dispositivos').DataTable({
                pageLength: 5,
                lengthChange: false,
                responsive: true,
                language: {
                    search: "Buscar:",
                    paginate: {
                        previous: "«",
                        next: "»"
                    }
                }
            });

            $('#filtroCodigo').on('input', function() {
                tabla.search(this.value).draw();
            });
        });
    </script>
</body>

</html>