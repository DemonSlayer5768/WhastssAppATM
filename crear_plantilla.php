<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombrePlantilla"];
    $cuerpo = $_POST["cuerpoPlantilla"];

    // Crear instancia de la clase Database y obtener conexión
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        $sql = "INSERT INTO whats_plantillas (nombre, cuerpo) VALUES (:nombre, :cuerpo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':cuerpo', $cuerpo);

        if ($stmt->execute()) {
            echo "Plantilla creada correctamente.";
        } else {
            echo "Error al crear la plantilla.";
        }
    } else {
        echo "No se pudo conectar a la base de datos.";
    }
}
