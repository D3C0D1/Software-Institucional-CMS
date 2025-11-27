<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

$db = getDB();

function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$type = isset($_GET['type']) ? sanitize($_GET['type']) : '';

try {
    if ($type === 'consultar') {
        $radicado = isset($_GET['radicado']) ? sanitize($_GET['radicado']) : '';
        if ($radicado === '') {
            json_response(['ok' => false, 'error' => 'Radicado requerido'], 400);
        }

        $stmt = $db->prepare("SELECT radicado, nombre, tipo, estado, fecha_radicado, resumen FROM pqrs WHERE radicado = :radicado LIMIT 1");
        $stmt->execute([':radicado' => $radicado]);
        $row = $stmt->fetch();
        if (!$row) {
            json_response(['ok' => false, 'error' => 'No se encontró el radicado'], 404);
        }
        $data = [
            'radicado' => $row['radicado'],
            'nombre'   => $row['nombre'],
            'tipo'     => $row['tipo'],
            'estado'   => $row['estado'],
            'fecha'    => formatearFecha($row['fecha_radicado'], 'd/m/Y H:i'),
            'resumen'  => $row['resumen']
        ];
        json_response(['ok' => true, 'data' => $data]);
    }
    else {
        json_response(['ok' => false, 'error' => 'Tipo no soportado'], 400);
    }
}
catch (Exception $e) {
    json_response(['ok' => false, 'error' => 'Error del servidor'], 500);
}
?>