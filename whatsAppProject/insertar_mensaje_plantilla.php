<?php
require_once 'database.php';
require __DIR__ . '/vendor/autoload.php';
header('Content-Type: application/json');

// Carga y validación de variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['WHATSAPP_API_TOKEN', 'WHATSAPP_PHONE_NUMBER_ID']);

$token = $_ENV['WHATSAPP_API_TOKEN'];
$phoneNumberId = $_ENV['WHATSAPP_PHONE_NUMBER_ID'];

if (!$token) {
    echo json_encode(["status" => "error", "message" => "Token de API no disponible."]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    // 1. Recibir datos
    $plantilla_id = $_POST['plantilla_id'] ?? '';
    $cuenta_id = $_POST['cuenta_id'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $status = 1;

    if (empty($plantilla_id) || empty($cuenta_id)) {
        echo json_encode(["status" => "error", "message" => "Plantilla y cuenta son requeridas."]);
        exit;
    }

    // 2. Obtener datos para enviar mensaje
    // Obtener número de teléfono
    $queryCuenta = "SELECT numero_telefono FROM whats_cuentas WHERE id = ?";
    $stmtCuenta = $conn->prepare($queryCuenta);
    $stmtCuenta->execute([$cuenta_id]);
    $rowCuenta = $stmtCuenta->fetch(PDO::FETCH_ASSOC);

    if (!$rowCuenta) {
        echo json_encode(["status" => "error", "message" => "Cuenta no encontrada."]);
        exit;
    }

    $numero_telefono = $rowCuenta['numero_telefono'];

    // Obtener cuerpo de plantilla
    $queryPlantilla = "SELECT cuerpo FROM whats_plantillas WHERE id = ?";
    $stmtPlantilla = $conn->prepare($queryPlantilla);
    $stmtPlantilla->execute([$plantilla_id]);
    $rowPlantilla = $stmtPlantilla->fetch(PDO::FETCH_ASSOC);

    if (!$rowPlantilla) {
        echo json_encode(["status" => "error", "message" => "Plantilla no encontrada."]);
        exit;
    }

    $cuerpo_plantilla = $rowPlantilla['cuerpo'];

    // 3. Enviar a la API de WhatsApp

    // $data = [
    //     'messaging_product' => 'whatsapp',
    //     'to' => $numero_telefono,
    //     'type' => 'text',
    //     'text' => ['body' => $cuerpo_plantilla]
    // ];


    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $numero_telefono,
        'type' => 'text',
        'text' => ['body' => $cuerpo_plantilla]
    ];


    $ch = curl_init("https://graph.facebook.com/v22.0/$phoneNumberId/messages");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_POST, true);


    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // 4. Insertar el mensaje en la base de datos
    $sql = "INSERT INTO whats_mensajes_plantilla (plantilla_id, cuenta_id, asunto, created, status)
            VALUES (:plantilla_id, :cuenta_id, :asunto, NOW(), :status)";
    $stmt = $conn->prepare($sql);

    // Si hubo error en la API, marcamos status como 0 (fallido)
    $status_to_store = ($http_code === 200) ? 1 : 0;

    $stmt->execute([
        ':plantilla_id' => $plantilla_id,
        ':cuenta_id' => $cuenta_id,
        ':asunto' => $asunto,
        ':status' => $status_to_store
    ]);

    $id_mensaje = $conn->lastInsertId();

    // 5. Validar respuesta de WhatsApp API
    if ($http_code === 200) {
        echo json_encode([
            "status" => "success",
            "message" => "Mensaje enviado correctamente.",
            "id_mensaje" => $id_mensaje,
            "numero" => $numero_telefono,
            "mensaje" => $cuerpo_plantilla,
            "api_response" => json_decode($response, true),
            "input_data" => [
                "plantilla_id" => $plantilla_id,
                "cuenta_id" => $cuenta_id,
                "asunto" => $asunto
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Falló el envío de WhatsApp. Código HTTP: $http_code",
            "curl_error" => $curl_error,
            "api_response" => $response,
            "id_mensaje" => $id_mensaje // Aún así registramos el intento
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error general: " . $e->getMessage()]);
}
