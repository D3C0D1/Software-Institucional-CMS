<?php
/**
 * Script de instalación automática de la base de datos
 * Ejecutar una sola vez: http://localhost/sitio_web/instalar_bd.php
 */

// Configuración de conexión
$host = 'localhost';
$user = 'root';
$pass = 'mysql';
$dbname = 'blog_cecar';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Instalación Base de Datos - CECAR Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f6f9;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #21A84B;
            border-bottom: 3px solid #21A84B;
            padding-bottom: 10px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #17a2b8;
            margin: 20px 0;
        }
        ul {
            line-height: 1.8;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #21A84B;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #1a8a3d;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🚀 Instalación de Base de Datos - CECAR Blog</h1>";

try {
    // Paso 1: Conectar sin especificar base de datos
    echo "<h3>Paso 1: Conectando al servidor MySQL...</h3>";
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='success'>✓ Conexión exitosa al servidor MySQL</div>";

    // Paso 2: Crear la base de datos
    echo "<h3>Paso 2: Creando base de datos...</h3>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<div class='success'>✓ Base de datos '$dbname' creada exitosamente</div>";

    // Paso 3: Seleccionar la base de datos
    $pdo->exec("USE `$dbname`");
    echo "<div class='success'>✓ Base de datos seleccionada</div>";

    // Paso 4: Leer y ejecutar el archivo SQL
    echo "<h3>Paso 3: Importando estructura y datos...</h3>";
    
    $sqlFile = __DIR__ . '/database.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("El archivo database.sql no se encontró en: $sqlFile");
    }

    $sql = file_get_contents($sqlFile);
    
    // Dividir el archivo en consultas individuales
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^(--|\/\*)/', $stmt);
        }
    );

    $count = 0;
    foreach ($statements as $statement) {
        if (!empty(trim($statement))) {
            $pdo->exec($statement);
            $count++;
        }
    }

    echo "<div class='success'>✓ Se ejecutaron $count consultas SQL exitosamente</div>";

    // Verificar las tablas creadas
    echo "<h3>Paso 4: Verificando instalación...</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div class='info'>";
    echo "<strong>Tablas creadas:</strong><ul>";
    foreach ($tables as $table) {
        echo "<li>✓ $table</li>";
    }
    echo "</ul></div>";

    // Contar registros
    $stmt = $pdo->query("SELECT COUNT(*) FROM articulos");
    $articulos = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM categorias");
    $categorias = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    $usuarios = $stmt->fetchColumn();

    echo "<div class='success'>";
    echo "<strong>Datos iniciales:</strong><ul>";
    echo "<li><strong>$articulos</strong> artículos de ejemplo</li>";
    echo "<li><strong>$categorias</strong> categorías</li>";
    echo "<li><strong>$usuarios</strong> usuario administrador</li>";
    echo "</ul></div>";

    echo "<h2>✅ ¡Instalación Completada Exitosamente!</h2>";
    
    echo "<div class='info'>";
    echo "<h3>📋 Información de Acceso:</h3>";
    echo "<p><strong>Panel de Administración:</strong><br>";
    echo "<code>http://localhost/sitio_web/admin/login.php</code></p>";
    echo "<p><strong>Usuario:</strong> <code>admin@cecar.edu.co</code><br>";
    echo "<strong>Contraseña:</strong> <code>admin123</code></p>";
    echo "<p><strong>Blog Público:</strong><br>";
    echo "<code>http://localhost/sitio_web/blog.php</code></p>";
    echo "</div>";

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='blog.php' class='btn'>Ver Blog</a> ";
    echo "<a href='admin/login.php' class='btn'>Ir al Panel Admin</a>";
    echo "</div>";

    echo "<div class='info' style='margin-top: 30px;'>";
    echo "<strong>⚠️ IMPORTANTE:</strong><br>";
    echo "Por seguridad, elimina este archivo después de la instalación:<br>";
    echo "<code>rm /Applications/AMPPS/www/sitio_web/instalar_bd.php</code>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>❌ Error de Base de Datos:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h3>💡 Solución Alternativa:</h3>";
    echo "<p>Si este script no funciona, puedes importar manualmente:</p>";
    echo "<ol>";
    echo "<li>Abre phpMyAdmin: <code>http://localhost/phpmyadmin</code></li>";
    echo "<li>Crea una base de datos llamada: <code>blog_cecar</code></li>";
    echo "<li>Selecciona la base de datos</li>";
    echo "<li>Ve a la pestaña 'Importar'</li>";
    echo "<li>Selecciona el archivo <code>database.sql</code></li>";
    echo "<li>Haz clic en 'Continuar'</li>";
    echo "</ol>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<strong>❌ Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "    </div>
</body>
</html>";
?>
