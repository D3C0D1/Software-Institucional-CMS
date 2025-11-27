<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

$db = getDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    setMensaje('ID de artículo no válido', 'danger');
    header('Location: articulos.php');
    exit;
}

// Obtener artículo
$stmt = $db->prepare("SELECT * FROM articulos WHERE id = :id");
$stmt->execute([':id' => $id]);
$articulo = $stmt->fetch();

if (!$articulo) {
    setMensaje('Artículo no encontrado', 'danger');
    header('Location: articulos.php');
    exit;
}

try {
    // Eliminar imagen del servidor
    if ($articulo['imagen_principal'] && file_exists('../' . $articulo['imagen_principal'])) {
        @unlink('../' . $articulo['imagen_principal']);
    }

    // Eliminar artículo (CASCADE eliminará las relaciones en articulo_categoria)
    $stmt = $db->prepare("DELETE FROM articulos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    setMensaje('Artículo eliminado exitosamente', 'success');
} catch (PDOException $e) {
    setMensaje('Error al eliminar el artículo: ' . $e->getMessage(), 'danger');
}

header('Location: articulos.php');
exit;
