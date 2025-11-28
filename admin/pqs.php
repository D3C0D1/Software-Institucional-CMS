<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

$db = getDB();
$mensaje = getMensaje();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'responder') {
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)($_POST['id'] ?? 0);
        $respuesta = trim($_POST['respuesta'] ?? '');
        $estado = $_POST['estado'] ?? 'resuelto';
        if ($id <= 0 || $respuesta === '' || !in_array($estado, ['radicado','en_proceso','resuelto','cerrado'])) {
            echo json_encode(['ok'=>false,'error'=>'Datos inválidos']); exit;
        }
        try{
            $hasPolicaribe = false;
            try {
                $chk = $db->query("SHOW COLUMNS FROM pqrs LIKE 'policaribe'");
                $hasPolicaribe = $chk && $chk->fetch() ? true : false;
            } catch (Exception $e) { $hasPolicaribe = false; }
            if ($hasPolicaribe) {
                $stmt = $db->prepare('UPDATE pqrs SET respuesta = :r, policaribe = :r, estado = :e WHERE id = :id');
            } else {
                $stmt = $db->prepare('UPDATE pqrs SET respuesta = :r, estado = :e WHERE id = :id');
            }
            $stmt->execute([':r'=>$respuesta, ':e'=>$estado, ':id'=>$id]);
            echo json_encode(['ok'=>true]);
        }catch(Exception $e){ echo json_encode(['ok'=>false,'error'=>'Error de servidor']); }
        exit;
    }
    if ($accion === 'detalle') {
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { echo json_encode(['ok'=>false,'error'=>'ID inválido']); exit; }
        try {
            $stmt = $db->prepare('SELECT id, radicado, nombre, identificacion, correo, telefono, tipo, resumen, detalle, estado, fecha_radicado FROM pqrs WHERE id = :id LIMIT 1');
            $stmt->execute([':id'=>$id]);
            $p = $stmt->fetch();
            if (!$p) { echo json_encode(['ok'=>false,'error'=>'No encontrado']); exit; }
            $st2 = $db->prepare('SELECT id, nombre_original, ruta, mime, size, fecha_subida FROM pqrs_adjuntos WHERE pqrs_id = :id ORDER BY fecha_subida DESC');
            $st2->execute([':id'=>$id]);
            $adj = $st2->fetchAll();
            echo json_encode(['ok'=>true,'data'=>['pqrs'=>$p,'adjuntos'=>$adj]]);
        } catch (Exception $e) {
            echo json_encode(['ok'=>false,'error'=>'Error de servidor']);
        }
        exit;
    }
    if ($accion === 'estado') {
        $id = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'radicado';
        if ($id > 0 && in_array($estado, ['radicado','en_proceso','resuelto','cerrado'])) {
            $stmt = $db->prepare('UPDATE pqrs SET estado = :estado WHERE id = :id');
            $stmt->execute([':estado' => $estado, ':id' => $id]);
            setMensaje('Estado actualizado', 'success');
        }
        header('Location: pqs.php');
        exit;
    }
    if ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare('DELETE FROM pqrs WHERE id = :id');
            $stmt->execute([':id' => $id]);
            setMensaje('PQRS eliminada', 'success');
        }
        header('Location: pqs.php');
        exit;
    }
}

$f_estado = isset($_GET['estado']) ? sanitize($_GET['estado']) : '';
$f_tipo = isset($_GET['tipo']) ? sanitize($_GET['tipo']) : '';
$f_q = isset($_GET['q']) ? sanitize($_GET['q']) : '';

try {
    $db->exec("CREATE TABLE IF NOT EXISTS pqrs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        radicado VARCHAR(50) NOT NULL UNIQUE,
        nombre VARCHAR(150) NOT NULL,
        identificacion VARCHAR(50) NOT NULL,
        correo VARCHAR(150) NOT NULL,
        telefono VARCHAR(50) NOT NULL,
        tipo ENUM('felicitacion','peticion','queja','reclamo','sugerencia') NOT NULL,
        resumen VARCHAR(255) NOT NULL,
        detalle LONGTEXT NOT NULL,
        estado ENUM('radicado','en_proceso','resuelto','cerrado') DEFAULT 'radicado',
        respuesta LONGTEXT NULL,
        fecha_radicado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_tipo (tipo),
        INDEX idx_estado (estado)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $db->exec("ALTER TABLE pqrs ADD COLUMN respuesta LONGTEXT NULL");
    $db->exec("ALTER TABLE pqrs ADD COLUMN policaribe LONGTEXT NULL");
    $db->exec("CREATE TABLE IF NOT EXISTS pqrs_adjuntos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pqrs_id INT NOT NULL,
        nombre_original VARCHAR(255) NOT NULL,
        ruta VARCHAR(255) NOT NULL,
        mime VARCHAR(100) NOT NULL,
        size INT NOT NULL,
        fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (pqrs_id) REFERENCES pqrs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
} catch (Exception $e) {}

$sql = 'SELECT * FROM pqrs WHERE 1=1';
$params = [];
if ($f_estado) { $sql .= ' AND estado = :estado'; $params[':estado'] = $f_estado; }
if ($f_tipo) { $sql .= ' AND tipo = :tipo'; $params[':tipo'] = $f_tipo; }
if ($f_q) { $sql .= ' AND (radicado LIKE :q OR nombre LIKE :q OR resumen LIKE :q)'; $params[':q'] = "%$f_q%"; }
$sql .= ' ORDER BY fecha_radicado DESC';
$items = [];
try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll();
} catch (Exception $e) {
    $items = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQRSF - Gestión de PQRSF</title>
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
        .label-estado { text-transform: capitalize; }
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
                    <a href="pqs.php" class="active"><i class="fa fa-list"></i> Ver registro de PQRSF</a>
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
                    <a href="usuarios.php"><i class="fa fa-users"></i> Gestión de usuarios</a>
                </div>
                <div class="sidebar-toggle"><span><i class="fa fa-lock"></i> Sesión</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="logout.php"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
                </div>
            </div>

            <div class="col-md-10 content-wrapper">
                <h1>PQRSF - Gestión de PQRSF</h1>
                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $mensaje['tipo']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo htmlspecialchars($mensaje['texto']); ?>
                    </div>
                <?php endif; ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form method="GET" class="form-inline">
                            <div class="form-group">
                                <input type="text" name="q" class="form-control" placeholder="Buscar por radicado, nombre o resumen" value="<?php echo htmlspecialchars($f_q); ?>">
                            </div>
                            <div class="form-group">
                                <select name="estado" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <?php foreach(['radicado','en_proceso','resuelto','cerrado'] as $e): ?>
                                        <option value="<?php echo $e; ?>" <?php echo $f_estado===$e?'selected':''; ?>><?php echo str_replace('_',' ', ucfirst($e)); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="tipo" class="form-control">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach(['felicitacion','peticion','queja','reclamo','sugerencia'] as $t): ?>
                                        <option value="<?php echo $t; ?>" <?php echo $f_tipo===$t?'selected':''; ?>><?php echo ucfirst($t); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-default">Filtrar</button>
                        </form>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Radicado</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Resumen</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($items)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No hay PQRS registradas.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($items as $p): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($p['radicado']); ?></td>
                                            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                            <td><?php echo ucfirst($p['tipo']); ?></td>
                                            <td><?php echo htmlspecialchars($p['resumen']); ?></td>
                                            <td>
                                                <span class="label label-estado label-<?php 
                                                    echo $p['estado']==='radicado'?'default':($p['estado']==='en_proceso'?'warning':($p['estado']==='resuelto'?'success':'primary')); 
                                                ?>">
                                                    <?php echo str_replace('_',' ', ucfirst($p['estado'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatearFecha($p['fecha_radicado'], 'd/m/Y H:i'); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-responder" data-id="<?php echo $p['id']; ?>" data-radicado="<?php echo htmlspecialchars($p['radicado']); ?>" data-nombre="<?php echo htmlspecialchars($p['nombre']); ?>"><i class="fa fa-reply"></i></button>
                                                <form method="POST" style="display:inline-block">
                                                    <input type="hidden" name="accion" value="estado">
                                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                                    <select name="estado" class="form-control input-sm" onchange="this.form.submit()">
                                                        <?php foreach(['radicado','en_proceso','resuelto','cerrado'] as $e): ?>
                                                            <option value="<?php echo $e; ?>" <?php echo $p['estado']===$e?'selected':''; ?>><?php echo ucfirst(str_replace('_',' ', $e)); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </form>
                                                <form method="POST" style="display:inline-block" onsubmit="return confirm('¿Eliminar esta PQRS?')">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>var SITE_URL = '<?php echo SITE_URL; ?>';</script>
    <script>
      jQuery(function($){
        $('.sidebar-toggle').on('click', function(){
          $(this).toggleClass('collapsed');
          $(this).next('.sidebar-section').toggleClass('collapsed');
        });
      });
    </script>
    <div id="respModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" style="width:600px;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><i class="fa fa-reply"></i> Responder PQRSF</h4>
          </div>
          <div class="modal-body">
            <form id="respForm">
              <input type="hidden" name="accion" value="responder">
              <input type="hidden" name="id" value="">
              <div class="form-group">
                <label>Radicado</label>
                <input type="text" class="form-control" id="respRadicado" disabled>
              </div>
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" id="respNombre" disabled>
              </div>
              <div class="form-group">
                <label>Respuesta</label>
                <textarea class="form-control" name="respuesta" rows="6" required></textarea>
              </div>
              <div class="form-group">
                <label>Estado</label>
                <select class="form-control" name="estado">
                  <option value="resuelto" selected>Resuelto</option>
                  <option value="en_proceso">En proceso</option>
                  <option value="cerrado">Cerrado</option>
                  <option value="radicado">Radicado</option>
                </select>
              </div>
            </form>
            <div id="respAlert" class="alert" style="display:none"></div>
            <div id="respDetails" class="well well-sm" style="display:none; margin-top:10px"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="respSaveBtn"><i class="fa fa-save"></i> Guardar respuesta</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      jQuery(function($){
        $('.btn-responder').on('click', function(){
          var id = $(this).data('id');
          $('#respForm [name=id]').val(id);
          $('#respRadicado').val($(this).data('radicado'));
          $('#respNombre').val($(this).data('nombre'));
          $('#respAlert').hide().removeClass('alert-success alert-danger');
          $('#respDetails').hide().empty();
          $.ajax({ url: 'pqs.php', method: 'POST', data: { accion: 'detalle', id: id }, dataType: 'json' })
            .done(function(res){
              if(res && res.ok && res.data){
                var p = res.data.pqrs || {};
                var adj = res.data.adjuntos || [];
                function esc(s){ return String(s||'').replace(/[&<>"']/g, function(c){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[c]; }); }
                var html = '<table class="table table-condensed">'
                  + '<tr><th>Tipo</th><td>'+esc(p.tipo)+'</td></tr>'
                  + '<tr><th>Correo</th><td>'+esc(p.correo)+'</td></tr>'
                  + '<tr><th>Teléfono</th><td>'+esc(p.telefono)+'</td></tr>'
                  + '<tr><th>Fecha</th><td>'+esc(p.fecha_radicado)+'</td></tr>'
                  + '<tr><th>Resumen</th><td>'+esc(p.resumen)+'</td></tr>'
                  + '<tr><th>Detalle</th><td style="white-space:pre-wrap">'+esc(p.detalle)+'</td></tr>'
                  + '</table>';
                if(adj.length){
                  html += '<h5><i class="fa fa-paperclip"></i> Adjuntos</h5><ul class="list-unstyled">';
                  adj.forEach(function(a){
                    var href = a.ruta || '';
                    if(href && href.indexOf('http')!==0){ href = SITE_URL + '/' + href.replace(/^\/*/, ''); }
                    html += '<li><a href="'+esc(href)+'" target="_blank">'+esc(a.nombre_original||'Archivo')+'</a> <small>(' + esc(a.mime||'') + ')</small></li>';
                  });
                  html += '</ul>';
                }
                $('#respDetails').html(html).show();
              }
            });
          $('#respModal').modal('show');
        });
        $('#respSaveBtn').on('click', function(){
          var $form = $('#respForm');
          var $alert = $('#respAlert');
          $alert.hide().removeClass('alert-success alert-danger');
          $.ajax({ url: 'pqs.php', method: 'POST', data: $form.serialize(), dataType: 'json' })
            .done(function(res){ if(res && res.ok){ $alert.addClass('alert-success').text('Respuesta guardada').show(); setTimeout(function(){ $('#respModal').modal('hide'); location.reload(); }, 800);} else { $alert.addClass('alert-danger').text(res && res.error ? res.error : 'Error al responder').show(); } })
            .fail(function(){ $alert.addClass('alert-danger').text('Error de conexión').show(); });
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
