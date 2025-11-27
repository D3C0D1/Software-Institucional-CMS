<?php
ini_set('display_errors',1); error_reporting(E_ALL);
require_once __DIR__ . '/../../config.php';
$db = getDB();

$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($action === 'vaciar') {
  $db->exec('SET FOREIGN_KEY_CHECKS=0');
  $db->exec('DELETE FROM articulo_categoria');
  $del = $db->exec('DELETE FROM articulos');
  $db->exec('SET FOREIGN_KEY_CHECKS=1');
  header('Content-Type: application/json');
  echo json_encode(['ok'=>true,'borrados'=>$del]);
  exit;
}
if ($action === 'fechas') {
  $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '2015-11-07';
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) $fecha = '2015-11-07';
  $stmt = $db->prepare('UPDATE articulos SET fecha_publicacion = :f');
  $stmt->execute([':f'=>$fecha.' 00:00:00']);
  header('Content-Type: application/json');
  echo json_encode(['ok'=>true,'fecha'=>$fecha]);
  exit;
}
if ($action === 'import_html') {
  libxml_use_internal_errors(true);
  $default = [
    '/Applications/AMPPS/www/sitio_web/policaribe/noticias/participacion-en-la-mesa-sectorial-de-cultura-e-innovacion-ciudadana.html',
    '/Applications/AMPPS/www/sitio_web/policaribe/noticias/la-secretaria-de-educacion-de-sincelejo-aprueba-nuevos-programas-del-politecnico-del-caribe.html',
    '/Applications/AMPPS/www/sitio_web/policaribe/noticias/grados-policaribe.html'
  ];
  $files = isset($_GET['files']) ? (array)$_GET['files'] : $default;
  $inserted = [];
  foreach ($files as $file) {
    $html = @file_get_contents($file);
    if ($html === false) continue;
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xp = new DOMXPath($dom);
    $titleNode = $xp->query('//h2[@itemprop="name"]')->item(0);
    $title = $titleNode ? trim($titleNode->textContent) : pathinfo($file, PATHINFO_FILENAME);
    $timeNode = $xp->query('//time[@itemprop="datePublished"]')->item(0);
    $datetime = $timeNode && $timeNode->getAttribute('datetime') ? $timeNode->getAttribute('datetime') : '2015-11-07T00:00:00+00:00';
    $fecha = date('Y-m-d H:i:s', strtotime($datetime));
    $authorNode = $xp->query('//dd[contains(@class,"createdby")]//span[@itemprop="name"]')->item(0);
    $author = $authorNode ? trim($authorNode->textContent) : 'Policaribe';
    $imgNode = $xp->query('(//div[contains(@class,"entry-image")]//img | //div[contains(@class,"entry-gallery")]//img | //div[@itemprop="articleBody"]//img)[1]')->item(0);
    $imgRel = '';
    if ($imgNode && $imgNode->getAttribute('src')) {
      $src = html_entity_decode($imgNode->getAttribute('src'));
      $src = rawurldecode($src);
      if (strpos($src,'../')===0) {
        $imgRel = 'policaribe/' . ltrim(str_replace('../','',$src),'/');
      } else {
        $imgRel = ltrim($src,'/');
      }
      $parts = explode('/', $imgRel);
      $imgRel = implode('/', array_map('rawurlencode',$parts));
      if (!file_exists_rel($imgRel)) {
        $basename = basename($imgRel);
        $found = find_image_by_basename($basename);
        if ($found) $imgRel = $found;
      }
    }
    $bodyNode = $xp->query('//div[@itemprop="articleBody"]')->item(0);
    $contenido = $bodyNode ? trim(inner_html($dom, $bodyNode)) : '';
    if ($contenido === '') {
      // fallback to text-only
      $contenido = $bodyNode ? trim($bodyNode->textContent) : '';
    }
    $slug = ensure_unique_slug($db, createSlug($title));
    $stmt = $db->prepare('INSERT INTO articulos (titulo, slug, descripcion_corta, contenido_completo, imagen_principal, autor, fecha_publicacion, estado, destacado, fecha_creacion) VALUES (:t,:s,:d,:c,:i,:a,:f,\'publicado\',0,NOW())');
    $desc = mb_substr(strip_tags($contenido),0,160);
    $stmt->execute([':t'=>$title, ':s'=>$slug, ':d'=>$desc, ':c'=>$contenido, ':i'=>$imgRel, ':a'=>$author, ':f'=>$fecha]);
    $id = $db->lastInsertId();
    $inserted[] = ['id'=>$id,'slug'=>$slug,'titulo'=>$title,'imagen'=>$imgRel,'fecha'=>$fecha];
  }
  header('Content-Type: application/json');
  echo json_encode(['ok'=>true,'insertados'=>$inserted]);
  exit;
}

function pdf_text($path){
  $cmd = trim((string)shell_exec('command -v pdftotext'));
  if ($cmd) {
    $out = @shell_exec('pdftotext -q ' . escapeshellarg($path) . ' - -enc UTF-8');
    if (is_string($out) && strlen($out)) { return trim($out); }
  }
  $bin = @file_get_contents($path);
  if ($bin === false) return '';
  $txt = preg_replace('/[^\x20-\x7E\n\r]+/',' ', $bin);
  $txt = preg_replace('/\s+/',' ', $txt);
  return trim($txt);
}
function first_words($s,$n){
  $a = preg_split('/\s+/', trim($s));
  if (!$a) return '';
  return implode(' ', array_slice($a,0,min($n,count($a))));
}
function title_from_filename($path){
  $name = pathinfo($path, PATHINFO_FILENAME);
  $name = preg_replace('/\s+\(.+\)$/','',$name);
  $name = preg_replace('/[_-]+/',' ', $name);
  $name = trim($name);
  return ucwords(strtolower($name));
}
function rel($abs){
  $root = realpath(__DIR__ . '/../../');
  $abs = realpath($abs);
  if (strpos($abs,$root)===0) return trim(substr($abs, strlen($root)+1),'/');
  return $abs;
}
function urlify_path($rel){
  $parts = explode('/', $rel);
  $parts = array_map('rawurlencode', $parts);
  return implode('/', $parts);
}
function inner_html($dom, $node){
  $html = '';
  foreach ($node->childNodes as $child) {
    $html .= $dom->saveHTML($child);
  }
  return $html;
}
function abs_root(){
  return realpath(__DIR__ . '/../../');
}
function file_exists_rel($rel){
  $path = abs_root() . '/' . $rel;
  return file_exists($path);
}
function find_image_by_basename($basename){
  $base = abs_root() . '/policaribe/images';
  if (!is_dir($base)) return '';
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));
  foreach ($it as $f) {
    if ($f->isFile() && strtolower($f->getFilename()) === strtolower($basename)) {
      $full = $f->getPathname();
      $rel = trim(substr($full, strlen(abs_root())+1), '/');
      return urlify_path($rel);
    }
  }
  return '';
}
function ensure_unique_slug($db,$slug){
  $q = $db->prepare('SELECT COUNT(*) c FROM articulos WHERE slug = :s');
  $q->execute([':s'=>$slug]);
  if ($q->fetch()['c']>0) { return $slug . '-' . time(); }
  return $slug;
}

$items = [
  [
    'pdf'=>__DIR__ . '/../../noticias/noticia1/nota visita a UAJS  (1).pdf',
    'img'=>__DIR__ . '/../../noticias/noticia1/WhatsApp Image 2025-11-03 at 17.18.15.jpeg'
  ],
  [
    'pdf'=>__DIR__ . '/../../noticias/noticia2/Document.pdf',
    'img'=>__DIR__ . '/../../noticias/noticia2/WhatsApp Image 2025-11-03 at 17.04.15.jpeg'
  ]
];

$db->exec('SET FOREIGN_KEY_CHECKS=0');
$db->exec('DELETE FROM articulo_categoria');
$db->exec('DELETE FROM articulos');
$db->exec('SET FOREIGN_KEY_CHECKS=1');

$inserted = [];
foreach ($items as $it){
  $text = pdf_text($it['pdf']);
  $title = title_from_filename($it['pdf']);
  $slug = ensure_unique_slug($db, createSlug($title));
  $desc = 'Documento importado desde PDF.';
  $contenido = $text ? mb_substr($text,0,5000) : 'Contenido disponible en PDF adjunto.';
  $contenido .= "\n\nDescarga del PDF: " . SITE_URL . '/' . rel($it['pdf']);
  $img = urlify_path(rel($it['img']));
  $stmt = $db->prepare('INSERT INTO articulos (titulo, slug, descripcion_corta, contenido_completo, imagen_principal, autor, fecha_publicacion, estado, destacado, fecha_creacion) VALUES (:t,:s,:d,:c,:i,:a,NOW(),\'publicado\',0,NOW())');
  $stmt->execute([':t'=>$title, ':s'=>$slug, ':d'=>$desc, ':c'=>$contenido, ':i'=>$img, ':a'=>'Policaribe']);
  $id = $db->lastInsertId();
  $inserted[] = ['id'=>$id,'slug'=>$slug,'titulo'=>$title,'imagen'=>$img];
}
header('Content-Type: application/json');
echo json_encode(['ok'=>true,'insertados'=>$inserted]);
?>