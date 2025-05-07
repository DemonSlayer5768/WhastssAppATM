<?php
require_once 'conexion.php'; // exion db

header('Content-Type: application/json');

$id_template = $_GET['id'] ?? null;

if (!$id_mensaje) {
    echo json_encode(["status" => "error", "message" => "ID de mensaje no proporcionado."]);
    exit;
}

// Buscar datos del mensaje
$query = "SELECT id, cuerpo FROM whats_templates WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_template);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Mensaje no encontrado."]);
    exit;
}

$row = $result->fetch_assoc();
$id_template = $row['cuerpo'];


// Buscar cuerpo de plantilla
$queryPlantilla = "SELECT cuerpo FROM whats_plantillas WHERE id = ?";
$stmtPlantilla = $conn->prepare($queryPlantilla);
$stmtPlantilla->bind_param("i", $plantilla_id);
$stmtPlantilla->execute();
$resultPlantilla = $stmtPlantilla->get_result();

if ($resultPlantilla->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Plantilla no encontrada."]);
    exit;
}

$rowPlantilla = $resultPlantilla->fetch_assoc();
$cuerpo = $rowPlantilla['cuerpo'];

// --- Enviar mensaje ---
$data = [
    'numero_destino' => $numero_telefono,
    'mensaje' => $cuerpo
];

$ch = curl_init('http://localhost/WhatsApp_Api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["status" => "error", "message" => curl_error($ch)]);
    exit;
}

curl_close($ch);

// Respuesta final
echo json_encode(["status" => "success", "response" => $response]);
