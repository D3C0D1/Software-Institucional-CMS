<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

$db = getDB();
$mensaje = getMensaje();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'actualizar_perfil') {
        header('Content-Type: application/json; charset=utf-8');
        $id = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 0;
        if ($id <= 0) { echo json_encode(['ok'=>false,'error'=>'Sesión inválida']); exit; }
        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $pass_actual = $_POST['password_actual'] ?? '';
        $pass_nueva = $_POST['password_nueva'] ?? '';
        $pass_confirm = $_POST['password_confirm'] ?? '';
        if (!$nombre || !$email || !$pass_actual) { echo json_encode(['ok'=>false,'error'=>'Complete los campos requeridos']); exit; }
        try {
            $stmt = $db->prepare('SELECT id, nombre, email, password FROM usuarios WHERE id = :id LIMIT 1');
            $stmt->execute([':id'=>$id]);
            $u = $stmt->fetch();
            if (!$u) { echo json_encode(['ok'=>false,'error'=>'Usuario no encontrado']); exit; }
            $valid = password_verify($pass_actual, $u['password']) || $u['password'] === $pass_actual;
            if (!$valid) { echo json_encode(['ok'=>false,'error'=>'Contraseña actual incorrecta']); exit; }
            if ($email !== $u['email']) {
                $ch = $db->prepare('SELECT id FROM usuarios WHERE email = :e AND id != :id LIMIT 1');
                $ch->execute([':e'=>$email, ':id'=>$id]);
                if ($ch->fetch()) { echo json_encode(['ok'=>false,'error'=>'El usuario/correo ya existe']); exit; }
            }
            $set = ['nombre = :n', 'email = :e'];
            $params = [':n'=>$nombre, ':e'=>$email, ':id'=>$id];
            if ($pass_nueva !== '') {
                if (strlen($pass_nueva) < 6) { echo json_encode(['ok'=>false,'error'=>'La nueva contraseña es muy corta']); exit; }
                if ($pass_nueva !== $pass_confirm) { echo json_encode(['ok'=>false,'error'=>'La confirmación no coincide']); exit; }
                $set[] = 'password = :p';
                $params[':p'] = password_hash($pass_nueva, PASSWORD_BCRYPT);
            }
            $sql = 'UPDATE usuarios SET ' . implode(', ', $set) . ' WHERE id = :id';
            $upd = $db->prepare($sql);
            $upd->execute($params);
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_email'] = $email;
            echo json_encode(['ok'=>true]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['ok'=>false,'error'=>'Error de servidor']);
            exit;
        }
    }
    if ($accion === 'crear') {
        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? 'editor';
        $activo = isset($_POST['activo']) ? 1 : 0;
        if ($nombre && $email && $password && in_array($rol, ['admin','editor'])) {
            try {
                $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
                $stmt->execute([':email' => $email]);
                if ($stmt->fetch()) {
                    setMensaje('El usuario ya existe', 'warning');
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $ins = $db->prepare('INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (:n, :e, :p, :r, :a)');
                    $ins->execute([':n'=>$nombre, ':e'=>$email, ':p'=>$hash, ':r'=>$rol, ':a'=>$activo]);
                    setMensaje('Usuario creado', 'success');
                }
            } catch (Exception $e) { setMensaje('Error al crear usuario', 'danger'); }
        } else {
            setMensaje('Datos inválidos', 'danger');
        }
        header('Location: usuarios.php');
        exit;
    }
    if ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $db->prepare('DELETE FROM usuarios WHERE id = :id')->execute([':id' => $id]);
                setMensaje('Usuario eliminado', 'success');
            } catch (Exception $e) { setMensaje('Error al eliminar usuario', 'danger'); }
        }
        header('Location: usuarios.php');
        exit;
    }
}

$stmt = $db->query('SELECT id, nombre, email, rol, activo, ultimo_acceso, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC');
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-custom { background-color: #b5241b; border: none; border-radius: 0; margin-bottom: 0; }
        .navbar-custom .navbar-brand, .navbar-custom .navbar-nav > li > a { color: white; }
        .navbar-custom .navbar-nav > li > a:hover { background-color: #901913; }
        .sidebar { background-color: #2c3e50; min-height: calc(100vh - 50px); padding: 0; }
        .sidebar a { color: #ecf0f1; display: block; padding: 15px 20px; text-decoration: none; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background-color: #34495e; border-left-color: #b5241b; }
        .content-wrapper { padding: 20px; }
        .table-hover tbody tr:hover { background-color: #f5f5f5; }
        .sidebar-toggle{color:#fff;text-transform:uppercase;font-size:12px;padding:10px 20px;cursor:pointer;display:flex;align-items:center;justify-content:space-between}
        .sidebar-toggle span i{margin-right:8px}
        .sidebar-section{padding:0}
        .sidebar-section.collapsed{display:none}
        .sidebar-toggle .chevron{transition:transform .2s ease}
        .sidebar-toggle.collapsed .chevron{transform:rotate(-90deg)}
    </style>
</head>
<body>
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">
                    <i class="fa fa-newspaper-o"></i> Admin Policaribe
                </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo SITE_URL; ?>/blog.php" target="_blank"><i class="fa fa-external-link"></i> Ver Sitio</a></li>
                <li><a href="#" id="perfilLink"><i class="fa fa-user"></i> <?php echo $_SESSION['usuario_nombre']; ?></a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Salir</a></li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                <div class="sidebar-toggle"><span><i class="fa fa-envelope"></i> PQRSF</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="pqs.php"><i class="fa fa-list"></i> Ver registro de PQRSF</a>
                </div>
                <div class="sidebar-toggle"><span><i class="fa fa-newspaper-o"></i> Noticias</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="articulos.php?estado=publicado"><i class="fa fa-check"></i> Noticias actuales</a>
                    <a href="crear_articulo.php"><i class="fa fa-plus"></i> Crear noticias</a>
                    <a href="articulos.php"><i class="fa fa-list"></i> Registro de noticias</a>
                    <a href="categorias.php"><i class="fa fa-folder"></i> Categorías</a>
                    <a href="<?php echo SITE_URL; ?>/templates/plantilla-galeria.php" target="_blank"><i class="fa fa-eye"></i> Vista previa</a>
                </div>
                <div class="sidebar-toggle"><span><i class="fa fa-users"></i> Usuarios</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="usuarios.php" class="active"><i class="fa fa-users"></i> Gestión de usuarios</a>
                </div>
                <div class="sidebar-toggle"><span><i class="fa fa-lock"></i> Sesión</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="logout.php"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
                </div>
            </div>

            <div class="col-md-10 content-wrapper">
                <h1>Gestión de Usuarios</h1>
                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $mensaje['tipo']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo htmlspecialchars($mensaje['texto']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Crear usuario</div>
                            <div class="panel-body">
                                <form method="POST">
                                    <input type="hidden" name="accion" value="crear">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" name="nombre" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Usuario o correo</label>
                                        <input type="text" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Contraseña</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Rol</label>
                                        <select name="rol" class="form-control">
                                            <option value="editor">Editor</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="activo" checked> Activo</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Crear</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">Usuarios registrados</div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Usuario/Correo</th>
                                            <th>Rol</th>
                                            <th>Estado</th>
                                            <th>Último acceso</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $u): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                                <td><span class="label label-info"><?php echo htmlspecialchars($u['rol']); ?></span></td>
                                                <td><span class="label label-<?php echo $u['activo'] ? 'success' : 'default'; ?>"><?php echo $u['activo'] ? 'Activo' : 'Inactivo'; ?></span></td>
                                                <td><?php echo $u['ultimo_acceso'] ? formatearFecha($u['ultimo_acceso'], 'd/m/Y H:i') : '-'; ?></td>
                                                <td>
                                                    <form method="POST" style="display:inline-block" onsubmit="return confirm('¿Eliminar este usuario?')">
                                                        <input type="hidden" name="accion" value="eliminar">
                                                        <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
      jQuery(function($){
        $('.sidebar-toggle').on('click', function(){
          $(this).toggleClass('collapsed');
          $(this).next('.sidebar-section').toggleClass('collapsed');
        });
      });
    </script>
    <div id="perfilModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" style="width:500px;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><i class="fa fa-user"></i> Actualizar perfil</h4>
          </div>
          <div class="modal-body">
            <form id="perfilForm">
              <input type="hidden" name="accion" value="actualizar_perfil">
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>" required>
              </div>
              <div class="form-group">
                <label>Usuario o correo</label>
                <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($_SESSION['usuario_email']); ?>" required>
              </div>
              <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" class="form-control" name="password_actual" required>
              </div>
              <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" class="form-control" name="password_nueva">
              </div>
              <div class="form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" class="form-control" name="password_confirm">
              </div>
            </form>
            <div id="perfilAlert" class="alert" style="display:none"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="perfilSaveBtn"><i class="fa fa-save"></i> Guardar</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      jQuery(function($){
        $('#perfilLink').on('click', function(e){ e.preventDefault(); $('#perfilModal').modal('show'); });
        $('#perfilSaveBtn').on('click', function(){
          var $form = $('#perfilForm');
          var $alert = $('#perfilAlert');
          $alert.hide().removeClass('alert-success alert-danger');
          $.ajax({ url: 'usuarios.php', method: 'POST', data: $form.serialize(), dataType: 'json' })
            .done(function(res){ if(res && res.ok){ $alert.addClass('alert-success').text('Perfil actualizado').show(); setTimeout(function(){ $('#perfilModal').modal('hide'); location.reload(); }, 800);} else { $alert.addClass('alert-danger').text(res && res.error ? res.error : 'Error al actualizar').show(); } })
            .fail(function(){ $alert.addClass('alert-danger').text('Error de conexión').show(); });
        });
      });
    </script>
</body>
</html>