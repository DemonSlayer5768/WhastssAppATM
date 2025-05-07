<?php
require_once 'database.php';
require_once 'whatsapp_api.php';
require_once 'insertar_plantilla.php';
require __DIR__ . '/vendor/autoload.php';
header('Content-Type: application/json');

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$token = $_ENV['WHATSAPP_API_TOKEN'] ?? '';
$phoneNumberId = $_ENV['WHATSAPP_PHONE_NUMBER_ID'] ?? '';

if (!$token || !$phoneNumberId) {
    echo json_encode(["status" => "error", "message" => "Credenciales de WhatsApp no disponibles."]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Obtener parámetros
    $plantilla_id = $_POST['plantilla_id'] ?? '';
    $cuenta_id = $_POST['cuenta_id'] ?? '';
    $asunto = $_POST['asunto'] ?? '';

    if (empty($plantilla_id) || empty($cuenta_id)) {
        echo json_encode(["status" => "error", "message" => "Parámetros incompletos."]);
        exit;
    }

    // Obtener número
    $stmtCuenta = $conn->prepare("SELECT numero_telefono FROM whats_cuentas WHERE id = ?");
    $stmtCuenta->execute([$cuenta_id]);
    $rowCuenta = $stmtCuenta->fetch(PDO::FETCH_ASSOC);

    if (!$rowCuenta) {
        echo json_encode(["status" => "error", "message" => "Cuenta no encontrada."]);
        exit;
    }

    $numero_telefono = $rowCuenta['numero_telefono'];

    // Obtener cuerpo
    $stmtPlantilla = $conn->prepare("SELECT cuerpo FROM whats_plantillas WHERE id = ?");
    $stmtPlantilla->execute([$plantilla_id]);
    $rowPlantilla = $stmtPlantilla->fetch(PDO::FETCH_ASSOC);

    if (!$rowPlantilla) {
        echo json_encode(["status" => "error", "message" => "Plantilla no encontrada."]);
        exit;
    }

    $cuerpo_plantilla = $rowPlantilla['cuerpo'];

    // Enviar mensaje
    $result = enviarMensajeWhatsApp($numero_telefono, $cuerpo_plantilla, $token, $phoneNumberId);
    $http_code = $result['http_code'];
    $response = $result['response'];
    $curl_error = $result['curl_error'];

    $status_to_store = ($http_code === 200) ? 1 : 0;
    $id_mensaje = insertarMensaje($conn, $plantilla_id, $cuenta_id, $asunto, $status_to_store);

    if ($http_code === 200) {
        $responseData = json_decode($response, true);
        echo json_encode([
            "status" => "success",
            "message" => "Mensaje enviado correctamente.",
            "id_mensaje" => $id_mensaje,
            "numero" => $numero_telefono,
            "mensaje" => $cuerpo_plantilla,
            "api_response" => $responseData
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al enviar mensaje. HTTP: $http_code",
            "curl_error" => $curl_error,
            "api_response" => $response,
            "id_mensaje" => $id_mensaje
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Excepción: " . $e->getMessage()]);
}
