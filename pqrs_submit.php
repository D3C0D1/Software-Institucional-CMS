<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pqrs.html');
    exit;
}

$db = getDB();

try {
    $db->exec("CREATE TABLE IF NOT EXISTS pqrs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        radicado VARCHAR(50) NOT NULL UNIQUE,
        nombre VARCHAR(150) NOT NULL,
        identificacion VARCHAR(50) NOT NULL,
        correo VARCHAR(150) NOT NULL,
        telefono VARCHAR(50) NOT NULL,
        tipo ENUM('felicitacion','peticion','queja','reclamo','sugerencia') NOT NULL,
        resumen VARCHAR(255) NOT NULL,
        detalle LONGTEXT NOT NULL,
        estado ENUM('radicado','en_proceso','resuelto','cerrado') DEFAULT 'radicado',
        fecha_radicado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_tipo (tipo),
        INDEX idx_estado (estado)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $db->exec("CREATE TABLE IF NOT EXISTS pqrs_adjuntos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pqrs_id INT NOT NULL,
        nombre_original VARCHAR(255) NOT NULL,
        ruta VARCHAR(255) NOT NULL,
        mime VARCHAR(100) NOT NULL,
        size INT NOT NULL,
        fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (pqrs_id) REFERENCES pqrs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
} catch (Exception $e) {}

$nombre = sanitize($_POST['nombre'] ?? '');
$identificacion = sanitize($_POST['identificacion'] ?? '');
$correo = sanitize($_POST['correo'] ?? '');
$telefono = sanitize($_POST['telefono'] ?? '');
$tipo = sanitize($_POST['tipo'] ?? '');
$resumen = sanitize($_POST['resumen'] ?? '');
$detalle = sanitize($_POST['detalle'] ?? '');

if (!$nombre || !$identificacion || !$correo || !$telefono || !$tipo || !$resumen || !$detalle) {
    header('Location: pqrs.html?ok=0');
    exit;
}

if (!in_array($tipo, ['felicitacion','peticion','queja','reclamo','sugerencia'])) {
    header('Location: pqrs.html?ok=0');
    exit;
}

$radicado = 'PQRS-' . date('Ymd') . '-' . substr(strtoupper(bin2hex(random_bytes(3))), 0, 6);

try {
    $stmt = $db->prepare("INSERT INTO pqrs (radicado, nombre, identificacion, correo, telefono, tipo, resumen, detalle) 
                          VALUES (:radicado, :nombre, :identificacion, :correo, :telefono, :tipo, :resumen, :detalle)");
    $stmt->execute([
        ':radicado' => $radicado,
        ':nombre' => $nombre,
        ':identificacion' => $identificacion,
        ':correo' => $correo,
        ':telefono' => $telefono,
        ':tipo' => $tipo,
        ':resumen' => $resumen,
        ':detalle' => $detalle
    ]);
    $pqrsId = (int)$db->lastInsertId();
} catch (Exception $e) {
    header('Location: pqrs.html?ok=0');
    exit;
}

if (isset($_FILES['adjuntos']) && is_array($_FILES['adjuntos']['name'])) {
    $names = $_FILES['adjuntos']['name'];
    $types = $_FILES['adjuntos']['type'];
    $tmpNames = $_FILES['adjuntos']['tmp_name'];
    $errors = $_FILES['adjuntos']['error'];
    $sizes = $_FILES['adjuntos']['size'];

    for ($i = 0; $i < count($names); $i++) {
        if ($errors[$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        $archivo = [
            'name' => $names[$i],
            'type' => $types[$i],
            'tmp_name' => $tmpNames[$i],
            'error' => $errors[$i],
            'size' => $sizes[$i]
        ];

        $resultado = subirArchivo($archivo, 'pqrs');
        if ($resultado) {
            try {
                $stmtAdj = $db->prepare("INSERT INTO pqrs_adjuntos (pqrs_id, nombre_original, ruta, mime, size) 
                                          VALUES (:pqrs_id, :nombre_original, :ruta, :mime, :size)");
                $stmtAdj->execute([
                    ':pqrs_id' => $pqrsId,
                    ':nombre_original' => $resultado['nombre_original'],
                    ':ruta' => $resultado['ruta'],
                    ':mime' => $resultado['mime'],
                    ':size' => $resultado['size']
                ]);
            } catch (Exception $e) {}
        }
    }
}

header('Location: pqrs.html?ok=1&radicado=' . urlencode($radicado));
exit;
?>