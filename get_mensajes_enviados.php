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

    // Consulta con JOIN para traer el nombre de la plantilla
    $sql = "SELECT 
                m.num_destino, 
                m.adjunto, 
                m.asunto, 
                p.nombre AS plantilla_nombre 
            FROM 
                whats_mensajes_enviados m
            LEFT JOIN 
                whats_plantillas p ON m.id_plantilla = p.id
            ORDER BY 
                m.created DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);



    echo json_encode($mensajes);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener los mensajes: " . $e->getMessage()]);
}
