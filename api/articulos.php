<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

$db = getDB();

function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$type = isset($_GET['type']) ? sanitize($_GET['type']) : 'news';
$categoria = isset($_GET['categoria']) ? sanitize($_GET['categoria']) : '';

try {
    if ($type === 'news') {
        $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 6;
        $params = [];
        $sql = "SELECT a.id, a.titulo, a.slug, a.descripcion_corta, a.imagen_principal, a.fecha_publicacion
                FROM articulos a";
        if (!empty($categoria)) {
            $sql .= " INNER JOIN articulo_categoria ac ON ac.articulo_id = a.id
                      INNER JOIN categorias c ON c.id = ac.categoria_id AND c.slug = :categoria";
            $params[':categoria'] = $categoria;
        }
        $sql .= " WHERE a.estado = 'publicado' ORDER BY a.destacado DESC, a.fecha_publicacion DESC LIMIT :limit";

        $stmt = $db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $data = array_map(function($r){
            return [
                'id' => (int)$r['id'],
                'titulo' => $r['titulo'],
                'slug' => $r['slug'],
                'descripcion_corta' => $r['descripcion_corta'],
                'imagen_principal' => $r['imagen_principal'],
                'fecha' => formatearFecha($r['fecha_publicacion'], 'M d, Y')
            ];
        }, $rows);

        json_response(['ok' => true, 'data' => $data]);
    }
    elseif ($type === 'calendar-month') {
        $year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
        $month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('n'));
        if ($month < 1 || $month > 12) $month = intval(date('n'));

        $params = [
            ':year' => $year,
            ':month' => $month
        ];
        $sql = "SELECT DATE(a.fecha_publicacion) AS d
                FROM articulos a";
        if (!empty($categoria)) {
            $sql .= " INNER JOIN articulo_categoria ac ON ac.articulo_id = a.id
                      INNER JOIN categorias c ON c.id = ac.categoria_id AND c.slug = :categoria";
            $params[':categoria'] = $categoria;
        }
        $sql .= " WHERE a.estado = 'publicado' 
                   AND YEAR(a.fecha_publicacion) = :year 
                   AND MONTH(a.fecha_publicacion) = :month
                   GROUP BY DATE(a.fecha_publicacion)
                   ORDER BY d ASC";

        $stmt = $db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $dates = array_map(function($r){ return $r['d']; }, $rows);
        json_response(['ok' => true, 'year' => $year, 'month' => $month, 'dates' => $dates]);
    }
    elseif ($type === 'calendar-day') {
        $date = isset($_GET['date']) ? sanitize($_GET['date']) : date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            json_response(['ok' => false, 'error' => 'Fecha inválida'], 400);
        }

        $params = [':date' => $date];
        $sql = "SELECT a.id, a.titulo, a.slug, a.descripcion_corta, a.imagen_principal, a.fecha_publicacion
                FROM articulos a";
        if (!empty($categoria)) {
            $sql .= " INNER JOIN articulo_categoria ac ON ac.articulo_id = a.id
                      INNER JOIN categorias c ON c.id = ac.categoria_id AND c.slug = :categoria";
            $params[':categoria'] = $categoria;
        }
        $sql .= " WHERE a.estado = 'publicado' AND DATE(a.fecha_publicacion) = :date 
                   ORDER BY a.fecha_publicacion ASC";

        $stmt = $db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $data = array_map(function($r){
            return [
                'id' => (int)$r['id'],
                'titulo' => $r['titulo'],
                'slug' => $r['slug'],
                'descripcion_corta' => $r['descripcion_corta'],
                'imagen_principal' => $r['imagen_principal'],
                'fecha' => formatearFecha($r['fecha_publicacion'], 'Y-m-d')
            ];
        }, $rows);
        json_response(['ok' => true, 'date' => $date, 'data' => $data]);
    }
    else {
        json_response(['ok' => false, 'error' => 'Tipo no soportado'], 400);
    }
}
catch (Exception $e) {
    json_response(['ok' => false, 'error' => 'Error del servidor'], 500);
}
?>