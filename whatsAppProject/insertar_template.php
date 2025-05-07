<?php
function insertarMensaje($conn, $id, $nombre_plantilla, $categoria, $cuerpo)
{
    $sql = "INSERT INTO whats_templates (id, nombre_plantilla ,categoria ,cuerpo, idioma, estado, fecha_creacion, fecha_actualizacion)
            VALUES (:id, :nombre_plantilla, :categoria, :cuerpo, :idioma, :status, NOW(), NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':nombre_plantilla' => $nombre_plantilla,
        ':categoria' => $categoria,
        ':cuerpo' => $cuerpo,
        ':idioma' => 'es',
        ':status' => '1',  // 1  ACTIVA o 0 BAJA
    ]);
    return $conn->lastInsertId();
}
