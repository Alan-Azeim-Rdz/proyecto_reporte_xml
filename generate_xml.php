<?php
require 'db_config.php';

$sql = "SELECT id, nombre, edad, genero, nivel_estudio, area_interes, fecha_registro FROM encuestas";
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql .= " WHERE id = $id";
}

$result = $conn->query($sql);

$xmlContent = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xmlContent .= "<reporte_perfiles>\n";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $nombre = htmlspecialchars($row['nombre']);
        $edad = htmlspecialchars($row['edad']);
        $genero = htmlspecialchars($row['genero']);
        $estudio = htmlspecialchars($row['nivel_estudio']);
        $interes = htmlspecialchars($row['area_interes']);
        $fecha = htmlspecialchars($row['fecha_registro']);

        // Lógica de recomendación
        $recomendacion = "";
        if ($row['area_interes'] == "Tecnología e Informática") {
            $recomendacion = "Recomendamos cursos de programación y análisis de datos.";
        } elseif ($row['area_interes'] == "Artes y Humanidades") {
            $recomendacion = "Recomendamos talleres de diseño gráfico y escritura creativa.";
        } elseif ($row['area_interes'] == "Ciencias de la Salud") {
            $recomendacion = "Recomendamos seminarios sobre bienestar y medicina preventiva.";
        } else {
            $recomendacion = "Recomendamos cursos de administración y emprendimiento.";
        }

        $xmlContent .= "  <perfil_usuario id=\"$id\">\n";
        $xmlContent .= "    <datos_personales>\n";
        $xmlContent .= "      <nombre>$nombre</nombre>\n";
        $xmlContent .= "      <edad>$edad</edad>\n";
        $xmlContent .= "      <genero>$genero</genero>\n";
        $xmlContent .= "    </datos_personales>\n";
        $xmlContent .= "    <analisis_perfil>\n";
        $xmlContent .= "      <nivel_educativo>$estudio</nivel_educativo>\n";
        $xmlContent .= "      <interes_principal>$interes</interes_principal>\n";
        $xmlContent .= "      <recomendacion>$recomendacion</recomendacion>\n";
        $xmlContent .= "    </analisis_perfil>\n";
        $xmlContent .= "    <fecha_evaluacion>$fecha</fecha_evaluacion>\n";
        $xmlContent .= "  </perfil_usuario>\n";
    }
}

$xmlContent .= "</reporte_perfiles>";

$conn->close();

// Enviar encabezados para forzar la descarga del archivo XML
header('Content-Type: text/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="reporte_perfil.xml"');

// Imprimir el XML generado
echo $xmlContent;
?>
