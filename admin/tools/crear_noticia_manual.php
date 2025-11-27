<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../funciones.php';

$db = getDB();

$titulo = isset($_REQUEST['titulo']) ? $_REQUEST['titulo'] : 'Politécnico del Caribe fortalece la formación de sus estudiantes con visita académica a la Corporación Universitaria Antonio José de Sucre.';
$fecha_publicacion = isset($_REQUEST['fecha']) ? $_REQUEST['fecha'] : '2025-11-07 12:00:00';
$autor = isset($_REQUEST['autor']) ? $_REQUEST['autor'] : 'Policaribe';
$estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : 'publicado';
$destacado = isset($_REQUEST['destacado']) ? (int)$_REQUEST['destacado'] : 0;

$dirRel = trim(isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'showimagen/showimage/noticia 1/', '/');
$dir = __DIR__ . '/../../' . $dirRel . '/';
$baseUrl = SITE_URL . '/' . implode('/', array_map('rawurlencode', explode('/', $dirRel))) . '/';
$files = is_dir($dir) ? array_values(array_filter(scandir($dir), function($f){ return preg_match('/\.(jpe?g|png|webp|gif|avif)$/i', $f); })) : [];
sort($files);
$encoded = array_map(function($f){ return rawurlencode($f); }, $files);
$urls = array_map(function($ef) use ($baseUrl){ return $baseUrl . $ef; }, $encoded);

$imagen_principal = isset($encoded[0]) ? '/' . implode('/', array_map('rawurlencode', explode('/', $dirRel))) . '/' . $encoded[0] : '';

$parrafos = [];
$cuerpo = isset($_REQUEST['cuerpo']) ? $_REQUEST['cuerpo'] : '';
if ($cuerpo) {
    foreach (preg_split("/\r?\n/", $cuerpo) as $ln) { $ln = trim($ln); if ($ln !== '') { $parrafos[] = $ln; } }
}
if (empty($parrafos)) {
    $parrafos = [
        'El Politécnico del Caribe realizó una visita académica desde su programa de Entrenamiento y Preparación Física a las instalaciones de la Corporación Universitaria Antonio José de Sucre, en donde recibieron distintas charlas en varios espacios donde se trataron temas de psicología, fisioterapia y rehabilitación física además de primeros auxilios.'
    ];
}

$contenido = '';
foreach ($parrafos as $p) { $contenido .= '<p>'.htmlspecialchars($p, ENT_QUOTES, 'UTF-8').'</p>'; }
for ($i = 1; $i < count($urls); $i++) { $contenido .= '<p><img src="'.$urls[$i].'" alt=""></p>'; }

$slug = createSlug($titulo);
$st = $db->prepare('SELECT COUNT(*) FROM articulos WHERE slug = :s');
$st->execute([':s'=>$slug]);
if ($st->fetchColumn() > 0) { $slug = $slug.'-'.time(); }

$descripcion_corta = mb_substr($parrafos[0], 0, 180).'...';

$sql = 'INSERT INTO articulos (titulo, slug, descripcion_corta, contenido_completo, imagen_principal, autor, fecha_publicacion, estado, destacado, fecha_creacion) VALUES (:titulo, :slug, :desc, :cont, :img, :autor, :fecha, :estado, :destacado, NOW())';
$stmt = $db->prepare($sql);
$stmt->execute([
    ':titulo' => $titulo,
    ':slug' => $slug,
    ':desc' => $descripcion_corta,
    ':cont' => $contenido,
    ':img' => $imagen_principal,
    ':autor' => $autor,
    ':fecha' => $fecha_publicacion,
    ':estado' => $estado,
    ':destacado' => $destacado
]);

$articulo_id = $db->lastInsertId();

$catId = null;
foreach (['Últimas noticias','Noticias'] as $n) {
    $s = $db->prepare('SELECT id FROM categorias WHERE nombre = :n AND activo = 1 LIMIT 1');
    $s->execute([':n'=>$n]);
    $r = $s->fetch();
    if ($r && isset($r['id'])) { $catId = (int)$r['id']; break; }
}
if ($catId) {
    $s = $db->prepare('INSERT IGNORE INTO articulo_categoria (articulo_id, categoria_id) VALUES (:a, :c)');
    $s->execute([':a'=>$articulo_id, ':c'=>$catId]);
}

header('Content-Type: application/json');
echo json_encode(['ok'=>true,'id'=>$articulo_id,'slug'=>$slug,'imagen_principal'=>$imagen_principal,'imagenes'=>$urls], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
?>