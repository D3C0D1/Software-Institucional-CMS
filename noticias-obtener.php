<?php
require_once 'config.php';
require_once 'funciones.php';

$db = getDB();

$sql = "SELECT NOMBREN, DESCRIPCIONN, IMAGENURLN, CATEGORIA FROM noticias ORDER BY FECHAN DESC LIMIT 4";
$stmt = $db->prepare($sql);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['data' => $noticias]);
?>