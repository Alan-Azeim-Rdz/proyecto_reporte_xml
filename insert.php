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
        
        // Obtener todos los reportes de la base de datos
        $all_reports = $conn->query("SELECT id, nombre, fecha_registro FROM encuestas ORDER BY fecha_registro DESC");

        echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Éxito</title>";
        echo "<style>
            body{font-family:Arial,sans-serif;background-color:#f4f4f9;padding:20px;text-align:center;}
            .container{background:white;padding:30px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.1);max-width:800px;margin:auto;}
            h1{color:#28a745;}
            .btn{display:inline-block;padding:10px 15px;background-color:#007bff;color:white;text-decoration:none;border-radius:6px;font-weight:bold;font-size:14px;}
            .btn-success{background-color:#28a745; margin-bottom: 20px;}
            .btn-secondary{background-color:#6c757d;margin-top:20px;}
            .btn:hover{opacity:0.9;}
            table{width:100%;border-collapse:collapse;margin-top:20px;}
            th,td{padding:12px;border-bottom:1px solid #ddd;text-align:left;}
            th{background-color:#f8f9fa;}
            .scroll-box{max-height:300px;overflow-y:auto;border:1px solid #eee;margin-top:10px;border-radius:4px;}
        </style></head><body>";
        echo "<div class='container'><h1>¡Test Completado!</h1><p>Tus respuestas han sido procesadas correctamente.</p>";
        
        echo "<a href='generate_xml.php' class='btn btn-success'>📥 Descargar Reporte General (Todos los usuarios)</a>";
        
        echo "<h3>Historial de Reportes en el Sistema</h3>";
        echo "<div class='scroll-box'><table><thead><tr><th>Nombre</th><th>Fecha</th><th>Acción</th></tr></thead><tbody>";
        
        if ($all_reports && $all_reports->num_rows > 0) {
            while ($row = $all_reports->fetch_assoc()) {
                $is_current = ($row['id'] == $last_id) ? " (Tu reporte actual)" : "";
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "<strong>$is_current</strong></td>";
                echo "<td>" . $row['fecha_registro'] . "</td>";
                echo "<td><a href='generate_xml.php?id=" . $row['id'] . "' class='btn'>📥 Descargar XML</a></td>";
                echo "</tr>";
            }
        }
        
        echo "</tbody></table></div>";
        echo "<a href='index.html' class='btn btn-secondary'>Realizar otra prueba</a>";
        echo "</div></body></html>";
    } else {
        echo "<script>alert('Error al guardar: " . $conn->error . "'); window.location.href='index.html';</script>";
    }

    $conn->close();
} else {
    header("Location: index.html");
}
?>
