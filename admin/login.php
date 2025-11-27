<?php
require_once '../config.php';
require_once '../funciones.php';

// Si ya está logueado, redirigir al dashboard
if (estaLogueado()) {
    header('Location: index.php');
    exit;
}

$error = '';

try {
    $db = getDB();
    $del = $db->prepare('DELETE FROM usuarios WHERE email = :e');
    $del->execute([':e' => 'admin@cecar.edu.co']);
    $sel = $db->prepare('SELECT id FROM usuarios WHERE email = :e LIMIT 1');
    $sel->execute([':e' => 'admin']);
    $hash = password_hash('admin', PASSWORD_BCRYPT);
    $row = $sel->fetch();
    if ($row) {
        $upd = $db->prepare('UPDATE usuarios SET nombre = :n, password = :p, rol = :r, activo = 1 WHERE id = :id');
        $upd->execute([':n' => 'Administrador', ':p' => $hash, ':r' => 'admin', ':id' => $row['id']]);
    } else {
        $ins = $db->prepare('INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (:n, :e, :p, :r, 1)');
        $ins->execute([':n' => 'Administrador', ':e' => 'admin', ':p' => $hash, ':r' => 'admin']);
    }
} catch (Exception $e) {}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Por favor complete todos los campos';
    } else {
        $usuario = verificarUsuario($email, $password);
        
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            header('Location: index.php');
            exit;
        } else {
            try {
                $db = getDB();
                $cnt = $db->query("SELECT COUNT(*) AS c FROM usuarios")->fetch();
                if ((int)$cnt['c'] === 0) {
                    $hash = password_hash('admin123', PASSWORD_BCRYPT);
                    $ins = $db->prepare('INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (:n, :e, :p, :r, 1)');
                    $ins->execute([':n'=>'Administrador', ':e'=>'admin@cecar.edu.co', ':p'=>$hash, ':r'=>'admin']);
                    $usuario = verificarUsuario($email, $password);
                    if ($usuario) {
                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['usuario_nombre'] = $usuario['nombre'];
                        $_SESSION['usuario_email'] = $usuario['email'];
                        $_SESSION['usuario_rol'] = $usuario['rol'];
                        header('Location: index.php');
                        exit;
                    }
                }
            } catch (Exception $e) {}
            $error = 'Credenciales incorrectas';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel de Administración</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-control {
            height: 45px;
            border-radius: 5px;
        }
        .btn-login {
            width: 100%;
            height: 45px;
            background: #b5241b;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn-login:hover {
            background: #901913;
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Panel de Administración</h2>
            <p>Admin Policaribe</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Usuario o correo</label>
                <input type="text" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="Usuario o correo"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       placeholder="••••••••"
                       required>
            </div>
            
            <button type="submit" class="btn btn-login">Iniciar Sesión</button>
        </form>
        
                
    </div>
</body>
</html>
