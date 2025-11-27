<?php
require_once '../config.php';
require_once '../funciones.php';

$bloqueado = !in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
$error = '';
$exito = '';

if (!$bloqueado && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitize($_POST['nombre'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? 'editor';

    if (empty($nombre) || empty($email) || empty($password)) {
        $error = 'Completa todos los campos';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Correo inválido';
    } elseif (!in_array($rol, ['admin','editor'])) {
        $error = 'Rol inválido';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        $db = getDB();
        try {
            $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $exists = $stmt->fetch();
            if ($exists) {
                $error = 'El correo ya existe';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ins = $db->prepare('INSERT INTO usuarios (nombre, email, password, rol, activo, ultimo_acceso) VALUES (:nombre, :email, :password, :rol, 1, NULL)');
                $ins->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':password' => $hash,
                    ':rol' => $rol
                ]);
                $exito = 'Usuario creado: ' . htmlspecialchars($email) . ' con rol ' . htmlspecialchars($rol);
            }
        } catch (Exception $e) {
            $error = 'Error al crear usuario';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario empleado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .container-box { max-width: 640px; margin: 40px auto; background: #fff; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,.08); }
        .header { background: #21A84B; color: #fff; padding: 16px 20px; border-radius: 6px 6px 0 0; }
        .content { padding: 20px; }
        .btn-primary { background: #21A84B; border-color: #21A84B; }
        .btn-primary:hover { background: #1a8a3d; border-color: #1a8a3d; }
        .help { color: #666; font-size: 12px; margin-top: 10px; }
    </style>
    </head>
<body>
    <div class="container-box">
        <div class="header">
            <h3 style="margin:0">Alta de usuario empleado</h3>
        </div>
        <div class="content">
            <?php if ($bloqueado): ?>
                <div class="alert alert-warning">Disponible solo en entorno local</div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($exito)): ?>
                <div class="alert alert-success"><?php echo $exito; ?></div>
                <p class="help">Ahora puedes iniciar sesión en <a href="<?php echo ADMIN_URL; ?>/login.php"><?php echo ADMIN_URL; ?>/login.php</a></p>
            <?php endif; ?>

            <form method="POST" action="" <?php echo $bloqueado ? 'onsubmit="return false"' : ''; ?>>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del empleado" required>
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="email" class="form-control" placeholder="empleado@cecar.edu.co" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" class="form-control">
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Crear usuario</button>
                <a href="<?php echo ADMIN_URL; ?>/login.php" class="btn btn-default">Ir al login</a>
            </form>
            <p class="help">Usuario por defecto existente: <code>admin@cecar.edu.co</code> / <code>admin123</code></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>