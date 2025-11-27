<?php
require_once 'config.php';

/**
 * Obtener todas las categorías activas
 */
function obtenerCategorias() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM categorias WHERE activo = 1 ORDER BY orden ASC");
    return $stmt->fetchAll();
}

/**
 * Obtener artículos con filtros
 */
function obtenerArticulos($categoria_id = null, $limite = 10, $offset = 0, $busqueda = null) {
    $db = getDB();
    
    $sql = "SELECT DISTINCT a.*, GROUP_CONCAT(c.nombre SEPARATOR ', ') as categorias
            FROM articulos a
            LEFT JOIN articulo_categoria ac ON a.id = ac.articulo_id
            LEFT JOIN categorias c ON ac.categoria_id = c.id
            WHERE a.estado = 'publicado' AND a.fecha_publicacion <= NOW()";
    
    $params = [];
    
    if ($categoria_id) {
        $sql .= " AND ac.categoria_id = :categoria_id";
        $params[':categoria_id'] = $categoria_id;
    }
    
    if ($busqueda) {
        $sql .= " AND (a.titulo LIKE :busqueda OR a.descripcion_corta LIKE :busqueda)";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }
    
    $sql .= " GROUP BY a.id ORDER BY a.fecha_publicacion DESC LIMIT :limite OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener artículos destacados
 */
function obtenerArticulosDestacados($limite = 6) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM articulos 
                          WHERE estado = 'publicado' 
                          AND destacado = 1 
                          AND fecha_publicacion <= NOW()
                          ORDER BY fecha_publicacion DESC 
                          LIMIT :limite");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Obtener un artículo por slug
 */
function obtenerArticuloPorSlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, GROUP_CONCAT(c.nombre SEPARATOR ', ') as categorias
                          FROM articulos a
                          LEFT JOIN articulo_categoria ac ON a.id = ac.articulo_id
                          LEFT JOIN categorias c ON ac.categoria_id = c.id
                          WHERE a.slug = :slug AND a.estado = 'publicado'
                          GROUP BY a.id");
    $stmt->execute([':slug' => $slug]);
    
    $articulo = $stmt->fetch();
    
    if ($articulo) {
        // Incrementar visitas
        $updateStmt = $db->prepare("UPDATE articulos SET visitas = visitas + 1 WHERE id = :id");
        $updateStmt->execute([':id' => $articulo['id']]);
    }
    
    return $articulo;
}

/**
 * Contar total de artículos
 */
function contarArticulos($categoria_id = null, $busqueda = null) {
    $db = getDB();
    
    $sql = "SELECT COUNT(DISTINCT a.id) as total
            FROM articulos a
            LEFT JOIN articulo_categoria ac ON a.id = ac.articulo_id
            WHERE a.estado = 'publicado' AND a.fecha_publicacion <= NOW()";
    
    $params = [];
    
    if ($categoria_id) {
        $sql .= " AND ac.categoria_id = :categoria_id";
        $params[':categoria_id'] = $categoria_id;
    }
    
    if ($busqueda) {
        $sql .= " AND (a.titulo LIKE :busqueda OR a.descripcion_corta LIKE :busqueda)";
        $params[':busqueda'] = '%' . $busqueda . '%';
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    
    return $result['total'];
}

/**
 * Obtener categoría por ID
 */
function obtenerCategoriaPorId($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM categorias WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

/**
 * Verificar credenciales de usuario
 */
function verificarUsuario($email, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email AND activo = 1");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($password, $usuario['password'])) {
        // Actualizar último acceso
        $updateStmt = $db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id");
        $updateStmt->execute([':id' => $usuario['id']]);
        return $usuario;
    }
    // Fallback: si la contraseña almacenada está en texto plano, migrar a hash
    if ($usuario && $usuario['password'] === $password) {
        $nuevoHash = password_hash($password, PASSWORD_BCRYPT);
        $upd = $db->prepare("UPDATE usuarios SET password = :hash, ultimo_acceso = NOW() WHERE id = :id");
        $upd->execute([':hash' => $nuevoHash, ':id' => $usuario['id']]);
        $usuario['password'] = $nuevoHash;
        return $usuario;
    }
    
    return false;
}

function subirArchivo($archivo, $carpeta = 'pqrs') {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $permitidos = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png',
        'application/zip',
        'application/x-rar-compressed',
        'video/mp4'
    ];

    if ($archivo['size'] > 10 * 1024 * 1024) {
        return false;
    }

    if (!in_array($archivo['type'], $permitidos)) {
        return false;
    }

    $destinoBase = UPLOAD_DIR . $carpeta . '/';
    if (!file_exists($destinoBase)) {
        mkdir($destinoBase, 0755, true);
    }

    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreSeguro = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $archivo['name']);
    $nombreArchivo = uniqid('pqrs_', true) . '_' . time() . '.' . $extension;
    $rutaDestino = $destinoBase . $nombreArchivo;

    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return [
            'ruta' => UPLOAD_URL . $carpeta . '/' . $nombreArchivo,
            'nombre_original' => $nombreSeguro,
            'mime' => $archivo['type'],
            'size' => $archivo['size']
        ];
    }

    return false;
}
?>
