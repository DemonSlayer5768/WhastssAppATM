<?php

require __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['WHATSAPP_API_TOKEN', 'WHATSAPP_PHONE_NUMBER_ID']);

$token = $_ENV['WHATSAPP_API_TOKEN'];
$phoneNumberId = $_ENV['WHATSAPP_PHONE_NUMBER_ID'];

$numero_destino = '523325921540s'; // Número en formato internacional sin '+'
$cuerpo_mensaje = 'Hola, este es un mensaje de prueba desde PHP usando texto plano.';

$resultado = enviarMensajeWhatsApp($numero_destino, $cuerpo_mensaje, $token, $phoneNumberId);
print_r($resultado);

// Función para enviar mensaje de texto plano
function enviarMensajeWhatsApp($numero_telefono, $cuerpo_mensaje, $token, $phoneNumberId)
{
    $message = [
        'messaging_product' => 'whatsapp',
        'to' => $numero_telefono,
        'type' => 'text',
        'text' => [
            'body' => $cuerpo_mensaje
        ]
    ];

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $url = "https://graph.facebook.com/v22.0/$phoneNumberId/messages";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'http_code' => $http_code,
        'curl_error' => $curl_error
    ];
}



// function obtenerPlantillaDesdeDB($nombre_plantilla)
// {
//     $db = new PDO('mysql:host=localhost;dbname=tu_db', 'usuario', 'contraseña');
//     $stmt = $db->prepare("SELECT * FROM whatsapp_templates WHERE nombre_plantilla = ? AND estado = 'APROBADA'");
//     $stmt->execute([$nombre_plantilla]);
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// // Uso:
// $plantilla = obtenerPlantillaDesdeDB('confirmacion_pedido');
// if (!$plantilla) {
//     die("Plantilla no encontrada o no aprobada.");
// }

// function enviarPlantillaDesdeDB($numero_telefono, $nombre_plantilla, $variables, $token, $phoneNumberId)
// {
//     $plantilla = obtenerPlantillaDesdeDB($nombre_plantilla);

//     $message = [
//         'messaging_product' => 'whatsapp',
//         'to' => $numero_telefono,
//         'type' => 'template',
//         'template' => [
//             'name' => $plantilla['nombre_plantilla'],
//             'language' => ['code' => $plantilla['idioma']],
//             'components' => [
//                 [
//                     'type' => 'body',
//                     'parameters' => array_map(function ($var) {
//                         return ['type' => 'text', 'text' => $var];
//                     }, $variables)
//                 ]
//             ]
//         ]
//     ];

//     // Resto del código de cURL (igual que antes)...
//     $headers = ["Authorization: Bearer $token", "Content-Type: application/json"];
//     $url = "https://graph.facebook.com/v22.0/$phoneNumberId/messages";

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//     $response = curl_exec($ch);
//     $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     return json_decode($response, true);
// }

// // Uso:
// $resultado = enviarPlantillaDesdeDB(
//     '523325921540',
//     'confirmacion_pedido',
//     ['Juan', 'ORD-12345', '25/05/2024'],
//     $token,
//     $phoneNumberId
// );
// print_r($resultado);
