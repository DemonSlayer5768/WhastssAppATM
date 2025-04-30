<?php
require __DIR__ . '/vendor/autoload.php';

// Cargar el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$token = $_ENV['token'] ?? null;
if (!$token) {
    echo json_encode(["status" => "error", "message" => "Token no disponible"]);
    exit;
}

// Obtener los datos que nos mandan
$numero_destino = $_POST['numero_destino'] ?? null;
$mensaje = $_POST['mensaje'] ?? null;

if (!$numero_destino || !$mensaje) {
    echo json_encode(["status" => "error", "message" => "Faltan parámetros"]);
    exit;
}

$accessToken = $token;
$phoneNumberId = "328945330313492";

$message = [
    'messaging_product' => 'whatsapp',
    'to' => $numero_destino,
    'type' => 'text',
    'text' => ['body' => $mensaje]
];

$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
];

// Configurar CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v20.0/$phoneNumberId/messages");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

// Manejar errores de CURL
if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    file_put_contents('error_log.txt', $error_msg . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Error en CURL"]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decodificar respuesta de Facebook
$responseData = json_decode($response, true);

// Manejar errores de Facebook
if (isset($responseData['error'])) {
    echo json_encode(["status" => "error", "message" => $responseData['error']['message']]);
    exit;
}

// Mensaje enviado correctamente
if (isset($responseData['messages'][0]['id'])) {
    echo json_encode(["status" => "success", "message_id" => $responseData['messages'][0]['id']]);
} else {
    echo json_encode(["status" => "error", "message" => "Mensaje no enviado correctamente"]);
}

// Opcional: guardar la respuesta en un archivo de log
file_put_contents('response_log.txt', $response . PHP_EOL, FILE_APPEND);
