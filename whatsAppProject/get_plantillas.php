<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();

    if (!$conn) {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo conectar a la base de datos."]);
        exit;
    }

    $sql = "SELECT id, nombre FROM whats_plantillas ORDER BY nombre ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($plantillas);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener las plantillas: " . $e->getMessage()]);
}
