<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $numero = $_POST["numero"];

    // Obtener la conexión
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        $sql = "INSERT INTO whats_cuentas (nombre, numero_telefono, fecha_creacion)
                VALUES (:nombre, :numero, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':numero', $numero);

        if ($stmt->execute()) {
            echo "Cuenta creada correctamente.";
        } else {
            echo "Error al crear la cuenta.";
        }
    } else {
        echo "No se pudo conectar a la base de datos.";
    }
}
