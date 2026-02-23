<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "alan";
$password = "123456789";
$dbname = "reporte_db";

$conn = new mysqli($host, $username, $password, $dbname);

// Configurar charset a utf8mb4 para soporte completo de caracteres
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
