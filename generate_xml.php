<?php
require 'db_config.php';

$sql = "SELECT id, nombre, edad, genero, nivel_estudio, area_interes, fecha_registro FROM encuestas";
$id = 0;
$result = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare($sql . " WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

// Mapeo de recomendaciones (Recomendación Senior: Escalabilidad)
$recomendaciones_map = [
    "Tecnología e Informática" => "Recomendamos cursos de programación y análisis de datos.",
    "Artes y Humanidades" => "Recomendamos talleres de diseño gráfico y escritura creativa.",
    "Ciencias de la Salud" => "Recomendamos seminarios sobre bienestar y medicina preventiva.",
    "Negocios y Finanzas" => "Recomendamos cursos de administración y emprendimiento."
];

$xmlContent = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xmlContent .= "<reporte_perfiles>\n";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_row = htmlspecialchars($row['id']);
        $nombre = htmlspecialchars($row['nombre']);
        $edad = htmlspecialchars($row['edad']);
        $genero = htmlspecialchars($row['genero']);
        $estudio = htmlspecialchars($row['nivel_estudio']);
        $interes = htmlspecialchars($row['area_interes']);
        $fecha = htmlspecialchars($row['fecha_registro']);

        // Obtener recomendación del mapa o usar default
        $recomendacion = $recomendaciones_map[$row['area_interes']] ?? "Recomendamos explorar diversas áreas para definir tu perfil.";

        $xmlContent .= "  <perfil_usuario id=\"$id_row\">\n";
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
