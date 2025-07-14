<?php
// db.php - Conexión a la base de datos

$host = 'localhost';
$db = 'db_inventario_sistemas';   // Base de datos
$user = 'root';       // Usuario (por defecto en XAMPP)
$pass = '';           // Contraseña (vacía por defecto en XAMPP)

$conn = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
