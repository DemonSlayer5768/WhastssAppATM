<?php
require_once 'conexion.php'; // tu archivo de conexión

header('Content-Type: application/json');

$id_mensaje = $_GET['id'] ?? null;

if (!$id_mensaje) {
    echo json_encode(["status" => "error", "message" => "ID de mensaje no proporcionado."]);
    exit;
}

// Buscar datos del mensaje
$query = "SELECT plantilla_id, cuenta_id FROM whats_mensajes_plantilla WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_mensaje);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Mensaje no encontrado."]);
    exit;
}

$row = $result->fetch_assoc();
$plantilla_id = $row['plantilla_id'];
$cuenta_id = $row['cuenta_id'];

// Buscar el número
$queryCuenta = "SELECT numero_telefono FROM whats_cuentas WHERE id = ?";
$stmtCuenta = $conn->prepare($queryCuenta);
$stmtCuenta->bind_param("i", $cuenta_id);
$stmtCuenta->execute();
$resultCuenta = $stmtCuenta->get_result();

if ($resultCuenta->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Cuenta no encontrada."]);
    exit;
}

$rowCuenta = $resultCuenta->fetch_assoc();
$numero_telefono = $rowCuenta['numero_telefono'];

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
$cuerpo_plantilla = $rowPlantilla['cuerpo'];

// --- Enviar mensaje ---
$data = [
    'numero_destino' => $numero_telefono,
    'mensaje' => $cuerpo_plantilla
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
