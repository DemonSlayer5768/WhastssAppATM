<?php
require_once 'database.php';
header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();

    $mensaje = $_POST['mensaje'] ?? '';
    $numero_destino = $_POST['numero_destino'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $created = date('Y-m-d H:i:s');
    $status = 1; // Activo

    if (empty($mensaje) || empty($numero_destino)) {
        echo json_encode(["status" => "error", "message" => "Mensaje y número destino son requeridos."]);
        exit;
    }

    $sql = "INSERT INTO whats_mensajes_directos (mensaje, numero_destino, asunto, created, status) 
            VALUES (:mensaje, :numero_destino, :asunto, :created, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':mensaje' => $mensaje,
        ':numero_destino' => $numero_destino,
        ':asunto' => $asunto,
        ':created' => $created,
        ':status' => $status
    ]);

    echo json_encode(["status" => "success", "message" => "Mensaje directo enviado correctamente."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al enviar mensaje directo: " . $e->getMessage()]);
}
