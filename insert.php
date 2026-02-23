<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar datos
    $nombre = $_POST['nombre'];
    $edad = intval($_POST['edad']);
    $genero = $_POST['genero'];
    $nivel_estudio = $_POST['nivel_estudio'];
    $area_interes = $_POST['area_interes'];

    // Usar Sentencias Preparadas (Recomendación Senior)
    $stmt = $conn->prepare("INSERT INTO encuestas (nombre, edad, genero, nivel_estudio, area_interes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $nombre, $edad, $genero, $nivel_estudio, $area_interes);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        $stmt->close();
        
        // Obtener todos los reportes de la base de datos
        $all_reports = $conn->query("SELECT id, nombre, fecha_registro FROM encuestas ORDER BY fecha_registro DESC");

        echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Éxito</title>";
        echo "<link rel='stylesheet' href='styles.css'></head><body>";
        echo "<div class='container'><h1>¡Test Completado!</h1><p>Tus respuestas han sido procesadas correctamente.</p>";
        
        echo "<div style='text-align: center; margin-bottom: 20px;'>";
        echo "<a href='generate_xml.php' class='btn btn-success'>📥 Descargar Reporte General (Todos los usuarios)</a>";
        echo "</div>";
        
        echo "<h3>Historial de Reportes en el Sistema</h3>";
        echo "<div class='scroll-box'><table><thead><tr><th>Nombre</th><th>Fecha</th><th>Acción</th></tr></thead><tbody>";
        
        if ($all_reports && $all_reports->num_rows > 0) {
            while ($row = $all_reports->fetch_assoc()) {
                $is_current = ($row['id'] == $last_id) ? " <span class='current-report'>(Tu reporte actual)</span>" : "";
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "<strong>$is_current</strong></td>";
                echo "<td>" . $row['fecha_registro'] . "</td>";
                echo "<td><a href='generate_xml.php?id=" . $row['id'] . "' class='btn'>📥 Descargar XML</a></td>";
                echo "</tr>";
            }
        }
        
        echo "</tbody></table></div>";
        echo "<div style='text-align: center; margin-top: 20px;'>";
        echo "<a href='index.html' class='btn btn-secondary'>Realizar otra prueba</a>";
        echo "</div>";
        echo "</div></body></html>";
    } else {
        echo "<script>alert('Error al guardar: " . $conn->error . "'); window.location.href='index.html';</script>";
    }

    $conn->close();
} else {
    header("Location: index.html");
}
?>
