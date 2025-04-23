<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_cuenta = $_POST["cuentas_id"];
    $asunto = $_POST["asunto"];
    $plantilla_id = !empty($_POST["plantilla_id"]) ? $_POST["plantilla_id"] : null;
    $mensaje = !empty($_POST["mensaje"]) ? $_POST["mensaje"] : null;
    $num_destino = trim($_POST["num_destino"]);

    // Conexión a DB
    $database = new Database();
    $conn = $database->getConnection();

    if (!$conn) {
        echo "Error de conexión.";
        exit;
    }

    // Si no se especificó número pero sí cuenta, buscarlo
    if (empty($num_destino) && !empty($nombre_cuenta)) {
        $stmt = $conn->prepare("SELECT telefono FROM whats_cuentas WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre_cuenta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $num_destino = $row["telefono"];
        } else {
            echo "Cuenta no encontrada.";
            exit;
        }
    }

    // Procesar archivos adjuntos
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

    // Insertar mensaje
    $sql = "INSERT INTO whats_mensajes_enviados 
            (created, modified, num_destino, adjunto, asunto, id_plantilla, mensaje)
            VALUES (NOW(), NOW(), :num_destino, :adjunto, :asunto, :plantilla_id, :mensaje)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':num_destino', $num_destino);
    $stmt->bindParam(':adjunto', $archivo_final, PDO::PARAM_LOB);
    $stmt->bindParam(':asunto', $asunto);
    $stmt->bindParam(':plantilla_id', $plantilla_id);
    $stmt->bindParam(':mensaje', $mensaje);

    if ($stmt->execute()) {
        echo "Mensaje enviado correctamente.";
    } else {
        echo "Error al enviar el mensaje.";
    }
}
