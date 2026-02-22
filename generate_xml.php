<?php
require 'db_config.php';

$sql = "SELECT id, nombre, edad, genero, nivel_estudio, area_interes, fecha_registro FROM encuestas";
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql .= " WHERE id = $id";
}

$result = $conn->query($sql);

$xml = new DOMDocument("1.0", "UTF-8");
$xml->formatOutput = true;

// Crear elemento raíz
$rootTag = $xml->createElement("reporte_perfiles");
$xml->appendChild($rootTag);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $perfilTag = $xml->createElement("perfil_usuario");
        $perfilTag->setAttribute("id", htmlspecialchars($row['id']));

        // Datos Personales
        $datosPersonalesTag = $xml->createElement("datos_personales");
        
        $nombreTag = $xml->createElement("nombre", htmlspecialchars($row['nombre']));
        $datosPersonalesTag->appendChild($nombreTag);

        $edadTag = $xml->createElement("edad", htmlspecialchars($row['edad']));
        $datosPersonalesTag->appendChild($edadTag);

        $generoTag = $xml->createElement("genero", htmlspecialchars($row['genero']));
        $datosPersonalesTag->appendChild($generoTag);

        $perfilTag->appendChild($datosPersonalesTag);

        // Datos Académicos y Análisis
        $analisisTag = $xml->createElement("analisis_perfil");

        $estudioTag = $xml->createElement("nivel_educativo", htmlspecialchars($row['nivel_estudio']));
        $analisisTag->appendChild($estudioTag);

        $interesTag = $xml->createElement("interes_principal", htmlspecialchars($row['area_interes']));
        $analisisTag->appendChild($interesTag);

        // Generar una categoría o recomendación basada en los datos (Lógica de reporte)
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

        $recomendacionTag = $xml->createElement("recomendacion", $recomendacion);
        $analisisTag->appendChild($recomendacionTag);

        $perfilTag->appendChild($analisisTag);

        // Metadatos
        $fechaTag = $xml->createElement("fecha_evaluacion", htmlspecialchars($row['fecha_registro']));
        $perfilTag->appendChild($fechaTag);

        $rootTag->appendChild($perfilTag);
    }
}

$conn->close();

// Enviar encabezados para forzar la descarga del archivo XML
header('Content-Type: text/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="reporte_perfil.xml"');

// Imprimir el XML generado
echo $xml->saveXML();
?>
