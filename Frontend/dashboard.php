<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Incluye la conexión a la base de datos
require_once "../Backend/db/db.php";

// Consulta los dispositivos (igual que lo haces en obtener_dispositivos.php)
$query = "SELECT d.IdDispositivo, d.CodigoPatrimonial, td.NombreTipo, a.descripcion, d.Estado, d.FechaRegistro, d.Observaciones
          FROM tb_dispositivos d
          JOIN Tb_Areas a ON d.IdArea = a.IdArea
          INNER JOIN tb_TipoDispositivo td ON d.IdTipoDispositivo = td.IdTipoDispositivo
          ORDER BY d.IdDispositivo DESC";

$result = $conn->query($query);

// Consulta tipos y áreas para otros usos
$tipos = $conn->query("SELECT IdTipoDispositivo, NombreTipo FROM Tb_TipoDispositivo");
$areas = $conn->query("SELECT IdArea, descripcion FROM Tb_Areas");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu CSS personalizado, debe ir después para sobreescribir Bootstrap -->
    <link rel="stylesheet" href="/SISTEMA_INVENTARIO/Backend/css/estilos.css" />

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <!-- jQuery (antes de otros scripts que lo necesiten) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Choices CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

    <?php include 'navbar.php'; ?>

    <!-- FORMULARIO -->
    <div class="container text-center mt-4">
        <div class="row justify-content-center">
            <!-- Formulario de Dispositivos -->
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-header bg-celeste-pastel text-dark d-flex align-items-center">
                        <i class="material-icons me-2">assignment_ind</i>
                        <span class="fw-bold">Dispositivos</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Cod. Patrimonial y Área (una fila) -->
                            <div class="col-md-6 mb-3">
                                <label for="cod_patrimonial" class="form-label">Cod. Patrimonial</label>
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    id="cod_patrimonial"
                                    name="cod_patrimonial"
                                    placeholder="Ingresar Cod. Patrimonial"
                                    autofocus />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="modalArea" class="form-label">Área</label>
                                <select class="form-select form-select-sm" id="modalArea" name="area">
                                    <option value="">Cargando áreas...</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Estado y Observación (segunda fila) -->
                            <div class="col-md-6 mb-3">
                                <label for="crearEstado" class="form-label">Estado</label>
                                <select class="form-select form-select-sm" id="crearEstado" name="CrearEstado">
                                    <option value="Operativo">Operativo</option>
                                    <option value="EnReparacion">En Reparación</option>
                                    <option value="Baja">De Baja</option>
                                    <option value="EnPrestamo">En Préstamo</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea
                                    class="form-control form-control-sm"
                                    id="observaciones"
                                    name="observaciones"
                                    placeholder="Ingresar Observación"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Tipo Dispositivo -->
            <div class="col-lg-6 col-md-8 col-sm-10 mt-4 mt-md-0">
                <div class="card shadow-sm border rounded-3">
                    <div class="card-header bg-verde-pastel bg-success text-black d-flex align-items-center">
                        <i class="material-icons me-2">location_on</i>
                        <span class="fw-bold">Tipo Dispositivo</span>
                    </div>

                    <!-- 🔧 Contenedor correcto para los campos -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="modalTipoDispositivo" class="form-label">Tipo de Dispositivo</label>
                                <select class="form-select form-select-sm" id="modalTipoDispositivo" name="TipoDispositivo">
                                    <option value="">Cargando Tipos de Dispositivos...</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campos CPU -->
                        <div id="camposCPU" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Procesador" class="form-label">Procesador</label>
                                    <input type="text" class="form-control form-control-sm" id="Procesador" name="Procesador" placeholder="Ej. Intel i5, Ryzen 7" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="RAM" class="form-label">Memoria RAM</label>
                                    <input type="text" class="form-control form-control-sm" id="RAM" name="RAM" placeholder="Ej. 8GB, 16GB" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Disco" class="form-label">Disco</label>
                                    <input type="text" class="form-control form-control-sm" id="Disco" name="Disco" placeholder="Ej. 1TB HDD, 512GB SSD" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Video" class="form-label">Tarjeta de Video</label>
                                    <input type="text" class="form-control form-control-sm" id="Video" name="Video" placeholder="Ej. NVIDIA GTX 1660, Radeon RX 580" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Placa" class="form-label">Placa</label>
                                    <input type="text" class="form-control form-control-sm" id="Placa" name="Placa" placeholder="Ej. ASUS B450, MSI Z490" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Laptop -->
                        <div id="camposLaptop" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Procesador" class="form-label">Procesador</label>
                                    <input type="text" class="form-control form-control-sm" id="Procesador" name="Procesador" placeholder="Ej. Intel i5, Ryzen 7" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="RAM" class="form-label">Memoria RAM</label>
                                    <input type="text" class="form-control form-control-sm" id="RAM" name="RAM" placeholder="Ej. 8GB, 16GB" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Disco" class="form-label">Disco</label>
                                    <input type="text" class="form-control form-control-sm" id="Disco" name="Disco" placeholder="Ej. 1TB HDD, 512GB SSD" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Video" class="form-label">Tarjeta de Video</label>
                                    <input type="text" class="form-control form-control-sm" id="Video" name="Video" placeholder="Ej. NVIDIA GTX 1660, Radeon RX 580" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Placa" class="form-label">Placa</label>
                                    <input type="text" class="form-control form-control-sm" id="Placa" name="Placa" placeholder="Ej. ASUS B450, MSI Z490" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Celular -->
                        <div id="camposCelular" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Tamaño_Pantalla" class="form-label">Tamaño de la Pantalla:</label>
                                    <input type="text" class="form-control form-control-sm" id="Tamaño_Pantalla" name="Tamaño_Pantalla" placeholder="Ej. 6.5 pulgadas" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Almacenamiento" class="form-label">Almacenamiento Interno:</label>
                                    <input type="text" class="form-control form-control-sm" id="Almacenamiento" name="Almacenamiento" placeholder="Ej. 128 GB" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Sistema_Operativo" class="form-label">Sistema Operativo</label>
                                    <input type="text" class="form-control form-control-sm" id="Sistema_Operativo" name="Sistema_Operativo" placeholder="Ej. Android 13 o iOS 17" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Tablet -->
                        <div id="camposTablet" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Tamaño_Pantalla" class="form-label">Tamaño de la Pantalla:</label>
                                    <input type="text" class="form-control form-control-sm" id="Tamaño_Pantalla" name="Tamaño_Pantalla" placeholder="Ej. 6.5 pulgadas" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Almacenamiento" class="form-label">Almacenamiento Interno:</label>
                                    <input type="text" class="form-control form-control-sm" id="Almacenamiento" name="Almacenamiento" placeholder="Ej. 128 GB" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Sistema_Operativo" class="form-label">Sistema Operativo</label>
                                    <input type="text" class="form-control form-control-sm" id="Sistema_Operativo" name="Sistema_Operativo" placeholder="Ej. Android 13 o iOS 17" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Servidor -->
                        <div id="camposServidor" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Red" class="form-label">Red:</label>
                                    <input type="text" class="form-control form-control-sm" id="Red" name="Red" placeholder="Ej. Ethernet, Wi-Fi" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Cantidad_Discos" class="form-label">Cantidad de Discos:</label>
                                    <input type="text" class="form-control form-control-sm" id="Cantidad_Discos" name="Cantidad_Discos" placeholder="Ej. 4 discos" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="UPS" class="form-label">UPS:</label>
                                    <input type="text" class="form-control form-control-sm" id="UPS" name="UPS" placeholder="Ej. APC 1500VA" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Sistema_Operativo" class="form-label">Sistema Operativo</label>
                                    <input type="text" class="form-control form-control-sm" id="Sistema_Operativo" name="Sistema_Operativo" placeholder="Ej. Windows Server 2022, Ubuntu Server 20.04" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Impresora -->
                        <div id="camposImpresora" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. L3250, MFC-L2750DW" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Epson, Brother, HP" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Teclado -->
                        <div id="camposTeclado" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. K120, G213" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Logitech, Microsoft" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Escaner -->
                        <div id="camposEscaner" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. ScanJet Pro 2500" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. HP, Canon, Epson" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Pantalla -->
                        <div id="camposPantalla" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. UltraSharp U2419H" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Dell, LG, Samsung" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Mouse -->
                        <div id="camposMouse" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. M170, MX Master 3" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Logitech, Microsoft" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Biometrico -->
                        <div id="camposBiometrico" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. ZKTeco MB360" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. ZKTeco, Suprema" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Lector de codigos -->
                        <div id="camposLector" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. DS2208, LI4278" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Zebra, Honeywell" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Estabilizador -->
                        <div id="camposEstabilizador" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. Forza FVR-1012" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Forza, APC" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Router -->
                        <div id="camposRouter" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. Archer C6, WR940N" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. TP-Link, Asus, MikroTik" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Switch -->
                        <div id="camposSwitch" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. TL-SG108E, CBS250" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. TP-Link, Cisco, Netgear" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Radio Enlace -->
                        <div id="camposRadio" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. PowerBeam M5, LiteBeam AC" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Ubiquiti, Mikrotik" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos Camaras -->
                        <div id="camposCamaras" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. DS-2CD1023G0" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Hikvision, Dahua" />
                                </div>
                            </div>
                        </div>

                        <!-- Campos NVR/DVR -->
                        <div id="camposNVR" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Modelo" class="form-label">Modelo:</label>
                                    <input type="text" class="form-control form-control-sm" id="Modelo" name="Modelo" placeholder="Ej. DS-7608NI-K1" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Marca" class="form-label">Marca:</label>
                                    <input type="text" class="form-control form-control-sm" id="Marca" name="Marca" placeholder="Ej. Hikvision, Dahua" />
                                </div>
                            </div>
                        </div>
                    </div> <!-- fin de card-body -->
                </div>
            </div>
        </div>

        <div class="mt-5"></div>

        <div class=".container.mt-3.p-3.bg-light.rounded.shadow-sm">
            <div class="d-flex align-items-center gap-3 m-4">
                <div class="filtro-contenedor" >
                    <select id="filtroTipoDispositivo" class="form-select h-100">
                        <option value="">Todos los Tipos</option>
                        <?php while ($tipo = $tipos->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($tipo['NombreTipo']) ?>">
                                <?= htmlspecialchars($tipo['NombreTipo']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex-grow-1">
                    <input type="text" id="inputBuscarCodigo" class="form-control" placeholder="Buscar Código Patrimonial" />
                </div>
                <div class="flex-shrink-0">
                    <button class="btn btn-primary" id="btnAgregarDispositivo">Agregar Dispositivo</button>
                </div>
            </div>

            <h4 class="mb-3">Listado de Dispositivos</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Código Patrimonial</th>
                        <th>Tipo Dispositivo</th>
                        <th>Área</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-dispositivos">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['IdDispositivo'] ?></td>
                                <td><?= htmlspecialchars($row['CodigoPatrimonial']) ?></td>
                                <td><?= htmlspecialchars($row['NombreTipo'] ?? 'Sin tipo') ?></td>
                                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                                <td>
                                    <button class="btn btn-sm 
            <?php echo $row['Estado'] === 'Operativo' ? 'btn-success-pastel' : ($row['Estado'] === 'EnReparacion' ? 'btn-warning-pastel' : ($row['Estado'] === 'Baja' ? 'btn-danger-pastel' : ($row['Estado'] === 'EnPrestamo' ? 'btn-primary-pastel' : 'btn-secondary-pastel'))); ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarEstado"
                                        data-id="<?php echo $row['IdDispositivo']; ?>"
                                        data-estado="<?php echo $row['Estado']; ?>"
                                        data-observacion="<?php echo htmlspecialchars($row['Observaciones'], ENT_QUOTES); ?>">
                                        <?php echo $row['Estado']; ?>
                                    </button>
                                </td>
                                <td><?= $row['FechaRegistro'] ?></td>
                                <td><?= htmlspecialchars($row['Observaciones']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay dispositivos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalEditarEstado" tabindex="-1" aria-labelledby="modalEditarEstadoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarEstadoLabel">Editar Estado del Dispositivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarEstado">
                        <!-- Campo oculto para el ID del dispositivo -->
                        <input type="hidden" id="modalIdDispositivo" name="idDispositivo">

                        <div class="mb-3">
                            <label for="modalEstado" class="form-label">Estado</label>
                            <select class="form-select" id="modalEstado" name="estado" required>
                                <option value="Operativo">Operativo</option>
                                <option value="EnReparacion">En Reparación</option>
                                <option value="Baja">De Baja</option>
                                <option value="EnPrestamo">En Préstamo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea
                                class="form-control form-control-sm"
                                id="observacion"
                                name="observacion"
                                placeholder="Ingresar Observación"
                                rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="js/agregar_dispositivo.js"></script>
    <script src="js/mostrar_areas.js"></script>
    <script src="js/mostrar_tipodispositivo.js"></script>
    <script src="js/ocultar_campos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="js/filtro_busqueda.js"></script>


    <script>
        $('#modalEditarEstado').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const idDispositivo = button.data('id');
            const estado = button.data('estado');
            const observacion = button.data('observacion') || '';

            console.log('🟡 Observación desde botón:', observacion); // ← agrega esto

            $('#modalIdDispositivo').val(idDispositivo);
            $('#modalEstado').val(estado);
            $('#observacion').val(observacion);

            console.log('🟢 Valor asignado a textarea:', $('#observacion').val()); // ← y esto
        });

        // Manejar el submit del formulario para enviar los datos por AJAX
        $('#formEditarEstado').submit(function(e) {
            e.preventDefault(); // Prevenir recarga

            var idDispositivo = $('#modalIdDispositivo').val();
            var estado = $('#modalEstado').val();
            var observacion = $('#observacion').val();

            $.ajax({
                url: '../Backend/dispositivos/actualizar_estado.php',
                type: 'POST',
                data: {
                    idDispositivo: idDispositivo,
                    estado: estado,
                    observacion: observacion
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    // Recargar la tabla sin recargar toda la página (opcional)
                    // actualizarTabla();

                    // Por simplicidad, recargamos toda la página para actualizar datos
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                    alert('Error al actualizar el dispositivo.');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>