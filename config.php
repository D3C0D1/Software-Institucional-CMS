<?php
/**
 * Archivo de configuración de base de datos
 * CECAR Blog System
 */

// Configuración de la base de datos por entorno
$__isLocalHost = isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);
$__isAmpps = strpos(__DIR__, '/Applications/AMPPS/') !== false;
if ($__isLocalHost || $__isAmpps) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'policaribe');
    define('DB_USER', 'root');
    define('DB_PASS', 'mysql');
    define('DB_CHARSET', 'utf8mb4');
} else {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'poli_policaribe');
    define('DB_USER', 'poli_admin');
    define('DB_PASS', 'A0347a1312#');
    define('DB_CHARSET', 'utf8mb4');
}

// Configuración del sitio
// Configuración del sitio
if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') {
    define('SITE_URL', 'http://localhost/Software-Institucional-CMS');
} else {
    define('SITE_URL', 'https://policaribe.edu.co');
}
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
if (!file_exists(UPLOAD_DIR . 'articulos/')) {
    mkdir(UPLOAD_DIR . 'articulos/', 0755, true);
}
if (!file_exists(UPLOAD_DIR . 'pqrs/')) {
    mkdir(UPLOAD_DIR . 'pqrs/', 0755, true);
}

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en HTTPS

// Zona horaria
date_default_timezone_set('America/Bogota');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Clase de conexión a la base de datos
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

/**
 * Función helper para obtener la conexión
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Función para sanitizar entrada
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Función para crear slug
 */
function createSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

/**
 * Función para formatear fecha
 */
function formatearFecha($fecha, $formato = 'd/m/Y H:i') {
    return date($formato, strtotime($fecha));
}

/**
 * Función para verificar si el usuario está logueado
 */
function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

/**
 * Función para requerir login
 */
function requerirLogin() {
    if (!estaLogueado()) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

/**
 * Función para subir imagen
 */
function subirImagen($archivo, $carpeta = 'articulos') {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return ['exito' => false, 'error' => 'Archivo no válido'];
    }

    $permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/avif'];
    if (!in_array($archivo['type'], $permitidos)) {
        return ['exito' => false, 'error' => 'Formato no permitido'];
    }

    if ($archivo['size'] > 8 * 1024 * 1024) {
        return ['exito' => false, 'error' => 'El archivo supera el límite de 8MB'];
    }

    $destinoBase = UPLOAD_DIR . $carpeta . '/';
    if (!file_exists($destinoBase)) {
        mkdir($destinoBase, 0755, true);
    }

    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreSeguro = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $archivo['name']);
    $nombreArchivo = uniqid('img_', true) . '_' . time() . '.' . $extension;
    $rutaDestino = $destinoBase . $nombreArchivo;

    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return [
            'exito' => true,
            'ruta' => UPLOAD_URL . $carpeta . '/' . $nombreArchivo,
            'nombre_original' => $nombreSeguro,
            'mime' => $archivo['type'],
            'size' => $archivo['size']
        ];
    }

    return ['exito' => false, 'error' => 'No se pudo mover el archivo'];
}

/**
 * Función para mostrar mensajes flash
 */
function setMensaje($tipo, $texto) {
    $_SESSION['mensaje'] = [
        'tipo' => $tipo,
        'texto' => $texto
    ];
}

function getMensaje() {
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        return $mensaje;
    }
    return null;
}
?>
