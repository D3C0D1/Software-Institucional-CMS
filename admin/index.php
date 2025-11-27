<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

// Obtener estadísticas
$db = getDB();

$stmt = $db->query("SELECT COUNT(*) as total FROM articulos");
$total_articulos = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM articulos WHERE estado = 'publicado'");
$articulos_publicados = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM categorias WHERE activo = 1");
$total_categorias = $stmt->fetch()['total'];

$stmt = $db->query("SELECT SUM(visitas) as total FROM articulos");
$total_visitas = $stmt->fetch()['total'] ?? 0;

// Artículos recientes
$stmt = $db->query("SELECT * FROM articulos ORDER BY fecha_creacion DESC LIMIT 5");
$articulos_recientes = $stmt->fetchAll();

$total_pqrs = 0;
$pqrs_radicadas = 0;
$pqrs_en_proceso = 0;
$pqrs_resueltas = 0;
$pqrs_recientes = [];

try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM pqrs");
    $total_pqrs = $stmt->fetch()['total'];
    $stmt = $db->query("SELECT COUNT(*) as total FROM pqrs WHERE estado = 'radicado'");
    $pqrs_radicadas = $stmt->fetch()['total'];
    $stmt = $db->query("SELECT COUNT(*) as total FROM pqrs WHERE estado = 'en_proceso'");
    $pqrs_en_proceso = $stmt->fetch()['total'];
    $stmt = $db->query("SELECT COUNT(*) as total FROM pqrs WHERE estado = 'resuelto'");
    $pqrs_resueltas = $stmt->fetch()['total'];
    $stmt = $db->query("SELECT * FROM pqrs ORDER BY fecha_radicado DESC LIMIT 5");
    $pqrs_recientes = $stmt->fetchAll();
} catch (Exception $e) {
    // Si las tablas no existen aún, los valores quedan en 0
}

$mensaje = getMensaje();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Panel de Administración</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .navbar-custom {
            background-color: #b5241b;
            border: none;
            border-radius: 0;
            margin-bottom: 0;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-nav > li > a {
            color: white;
        }
        .navbar-custom .navbar-nav > li > a:hover {
            background-color: #901913;
        }
        .sidebar {
            background-color: #2c3e50;
            min-height: calc(100vh - 50px);
            padding: 0;
        }
        .sidebar a {
            color: #ecf0f1;
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #34495e;
            border-left-color: #21A84B;
        }
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stat-box h3 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
        .stat-box .number {
            font-size: 36px;
            font-weight: bold;
            color: #b5241b;
        }
        .stat-box .icon {
            float: right;
            font-size: 40px;
            color: #ecf0f1;
        }
        .content-wrapper {
            padding: 20px;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .sidebar-toggle{color:#fff;text-transform:uppercase;font-size:12px;padding:10px 20px;cursor:pointer;display:flex;align-items:center;justify-content:space-between}
        .sidebar-toggle span i{margin-right:8px}
        .sidebar-section{padding:0}
        .sidebar-section.collapsed{display:none}
        .sidebar-toggle .chevron{transition:transform .2s ease}
        .sidebar-toggle.collapsed .chevron{transform:rotate(-90deg)}
    </style>
</head>
<body>
    <!-- Navbar -->
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
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <a href="index.php" class="active"><i class="fa fa-dashboard"></i> Dashboard</a>
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
                    <a href="usuarios.php"><i class="fa fa-users"></i> Gestión de usuarios</a>
                </div>
                <div class="sidebar-toggle"><span><i class="fa fa-lock"></i> Sesión</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="logout.php"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content-wrapper">
                <h1>Dashboard</h1>
                
                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $mensaje['tipo']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo htmlspecialchars($mensaje['texto']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-file-text icon"></i>
                            <h3>Total Artículos</h3>
                            <div class="number"><?php echo $total_articulos; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-check-circle icon"></i>
                            <h3>Publicados</h3>
                            <div class="number"><?php echo $articulos_publicados; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-folder icon"></i>
                            <h3>Categorías</h3>
                            <div class="number"><?php echo $total_categorias; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-eye icon"></i>
                            <h3>Total Visitas</h3>
                            <div class="number"><?php echo number_format($total_visitas); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-envelope icon"></i>
                            <h3>Total PQRSF</h3>
                            <div class="number"><?php echo $total_pqrs; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-inbox icon"></i>
                            <h3>Radicadas</h3>
                            <div class="number"><?php echo $pqrs_radicadas; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-cogs icon"></i>
                            <h3>En Proceso</h3>
                            <div class="number"><?php echo $pqrs_en_proceso; ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <i class="fa fa-check icon"></i>
                            <h3>Resueltas</h3>
                            <div class="number"><?php echo $pqrs_resueltas; ?></div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-newspaper-o"></i> Artículos Recientes</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Visitas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($articulos_recientes as $articulo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($articulo['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($articulo['autor']); ?></td>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $articulo['estado'] === 'publicado' ? 'success' : 
                                                ($articulo['estado'] === 'borrador' ? 'warning' : 'default'); 
                                        ?>">
                                            <?php echo ucfirst($articulo['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatearFecha($articulo['fecha_publicacion'], 'd/m/Y'); ?></td>
                                    <td><?php echo number_format($articulo['visitas']); ?></td>
                                    <td>
                                        <a href="editar_articulo.php?id=<?php echo $articulo['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="articulos.php" class="btn btn-default">Ver todos los artículos</a>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-envelope"></i> PQRS Recientes</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Radicado</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pqrs_recientes as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['radicado']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                    <td><?php echo ucfirst($p['tipo']); ?></td>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $p['estado'] === 'radicado' ? 'default' : 
                                                ($p['estado'] === 'en_proceso' ? 'warning' : 'success'); 
                                        ?>">
                                            <?php echo str_replace('_',' ', ucfirst($p['estado'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatearFecha($p['fecha_radicado'], 'd/m/Y H:i'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
