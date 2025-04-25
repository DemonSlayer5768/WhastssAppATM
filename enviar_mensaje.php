<?php
// Muestra los errores de PHP (solo en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'database.php';
header('Content-Type: application/json');

// Verifica que sea una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica que los parámetros necesarios estén presentes
    if (!isset($_POST['cuentas_id']) || !isset($_POST['asunto']) || !isset($_POST['num_destino'])) {
        echo json_encode(["status" => "error", "message" => "Faltan parámetros necesarios."]);
        exit;
    }

    // Obtener los parámetros
    $cuenta_id = $_POST["cuentas_id"];
    $asunto = $_POST["asunto"];
    $plantilla_id = !empty($_POST["plantilla_id"]) ? $_POST["plantilla_id"] : null;
    $mensaje = !empty($_POST["mensaje"]) ? $_POST["mensaje"] : null;
    $num_destino = trim($_POST["num_destino"]);

    // Crea la conexión a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Verificar si la conexión es exitosa
    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Error de conexión a la base de datos."]);
        exit;
    }

    // Buscar teléfono por ID si no se ingresó manualmente
    if (empty($num_destino) && !empty($cuenta_id)) {
        $stmt = $conn->prepare("SELECT telefono FROM whats_cuentas WHERE id = :id");
        $stmt->bindParam(':id', $cuenta_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $num_destino = $row["telefono"];
        } else {
            echo json_encode(["status" => "error", "message" => "Cuenta no encontrada."]);
            exit;
        }
    }

    // Procesar archivos adjuntos (si existen)
    $archivo_final = null;
    if (!empty($_FILES['archivoAdjunto']['tmp_name'][0])) {
        $contenido_total = '';
        foreach ($_FILES['archivoAdjunto']['tmp_name'] as $tmpFile) {
            if (file_exists($tmpFile)) {
                $contenido_total .= file_get_contents($tmpFile);
            }
        }
        $archivo_final = $contenido_total;
    }

    // Insertar el mensaje en la base de datos
    try {
        $sql = "INSERT INTO whats_mensajes_enviados 
                (created, modified, num_destino, adjunto, asunto, id_pantilla, mensaje)
                VALUES (NOW(), NOW(), :num_destino, :adjunto, :asunto, :id_pantilla, :mensaje)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':num_destino', $num_destino);
        $stmt->bindParam(':adjunto', $archivo_final, PDO::PARAM_LOB);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':id_pantilla', $plantilla_id);
        $stmt->bindParam(':mensaje', $mensaje);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Mensaje enviado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al enviar el mensaje."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
    } finally {
        $conn = null; // Cierra la conexión a la base de datos
    }
} else {
    // Si no es una solicitud POST
    echo json_encode(["status" => "error", "message" => "Método de solicitud no válido."]);
}
