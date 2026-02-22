<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $edad = intval($_POST['edad']);
    $genero = $conn->real_escape_string($_POST['genero']);
    $nivel_estudio = $conn->real_escape_string($_POST['nivel_estudio']);
    $area_interes = $conn->real_escape_string($_POST['area_interes']);

    $sql = "INSERT INTO encuestas (nombre, edad, genero, nivel_estudio, area_interes) VALUES ('$nombre', $edad, '$genero', '$nivel_estudio', '$area_interes')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Éxito</title>";
        echo "<style>body{font-family:Arial,sans-serif;background-color:#f4f4f9;padding:50px;text-align:center;}";
        echo ".container{background:white;padding:30px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.1);max-width:600px;margin:auto;}";
        echo "h1{color:#28a745;}.btn{display:inline-block;padding:12px 20px;margin-top:15px;background-color:#007bff;color:white;text-decoration:none;border-radius:6px;font-weight:bold;}";
        echo ".btn-secondary{background-color:#6c757d;margin-top:10px;}.btn:hover{opacity:0.9;}</style></head><body>";
        echo "<div class='container'><h1>¡Test Completado!</h1><p>Tus respuestas han sido procesadas correctamente.</p>";
        echo "<a href='generate_xml.php?id=$last_id' class='btn'>📥 Descargar Tu Reporte de Perfil (XML)</a> ";
        echo "<br><br><a href='index.html' class='btn btn-secondary'>Realizar otra prueba</a>";
        echo "</div></body></html>";
    } else {
        echo "<script>alert('Error al guardar: " . $conn->error . "'); window.location.href='index.html';</script>";
    }

    $conn->close();
} else {
    header("Location: index.html");
}
?>
