<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

// Obtener artículos
$db = getDB();
$busqueda = isset($_GET['busqueda']) ? sanitize($_GET['busqueda']) : '';
$estado = isset($_GET['estado']) ? sanitize($_GET['estado']) : '';

$sql = "SELECT a.*, GROUP_CONCAT(c.nombre SEPARATOR ', ') as categorias
        FROM articulos a
        LEFT JOIN articulo_categoria ac ON a.id = ac.articulo_id
        LEFT JOIN categorias c ON ac.categoria_id = c.id
        WHERE 1=1";

$params = [];

if (!empty($busqueda)) {
    $sql .= " AND (a.titulo LIKE :busqueda OR a.autor LIKE :busqueda)";
    $params[':busqueda'] = '%' . $busqueda . '%';
}

if (!empty($estado)) {
    $sql .= " AND a.estado = :estado";
    $params[':estado'] = $estado;
}

$sql .= " GROUP BY a.id ORDER BY a.fecha_creacion DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$articulos = $stmt->fetchAll();

$mensaje = getMensaje();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos - Panel de Administración</title>
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
            border-left-color: #b5241b;
        }
        .content-wrapper {
            padding: 20px;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .btn-crear {
            margin-bottom: 20px;
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
                <a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                <div class="sidebar-toggle"><span><i class="fa fa-envelope"></i> PQRSF</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="pqs.php"><i class="fa fa-list"></i> Ver registro de PQRSF</a>
                </div>
                <div class="sidebar-toggle"><span><i class="fa fa-newspaper-o"></i> Noticias</span> <i class="fa fa-chevron-down chevron"></i></div>
                <div class="sidebar-section">
                    <a href="articulos.php?estado=publicado"><i class="fa fa-check"></i> Noticias actuales</a>
                    <a href="crear_articulo.php"><i class="fa fa-plus"></i> Crear noticias</a>
                    <a href="articulos.php" class="active"><i class="fa fa-list"></i> Registro de noticias</a>
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
                <h1>Gestión de Artículos</h1>
                
                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $mensaje['tipo']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo htmlspecialchars($mensaje['texto']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <a href="crear_articulo.php" class="btn btn-success btn-crear">
                            <i class="fa fa-plus"></i> Crear Nuevo Artículo
                        </a>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="form-inline pull-right">
                            <div class="form-group">
                                <select name="estado" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="publicado" <?php echo $estado === 'publicado' ? 'selected' : ''; ?>>Publicado</option>
                                    <option value="borrador" <?php echo $estado === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                                    <option value="archivado" <?php echo $estado === 'archivado' ? 'selected' : ''; ?>>Archivado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" 
                                       name="busqueda" 
                                       class="form-control" 
                                       placeholder="Buscar..."
                                       value="<?php echo htmlspecialchars($busqueda); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php if (!empty($articulos)): ?>
                            <div class="list-group">
                                <?php foreach ($articulos as $articulo): ?>
                                    <div class="list-group-item">
                                        <div class="media">
                                            <div class="media-left" style="width:80px;">
                                                <?php if (!empty($articulo['imagen_principal'])): ?>
                                                    <img src="<?php echo preg_match('/^https?:\/\//i', $articulo['imagen_principal']) ? htmlspecialchars($articulo['imagen_principal']) : SITE_URL . '/' . htmlspecialchars($articulo['imagen_principal']); ?>" alt="" style="width:72px;height:72px;object-fit:cover;border-radius:6px;">
                                                <?php else: ?>
                                                    <div style="width:72px;height:72px;background:#eee;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#999;">N/A</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading" style="margin-top:0;">
                                                    <?php echo htmlspecialchars($articulo['titulo']); ?>
                                                    <small>
                                                        <span class="label label-<?php echo $articulo['estado'] === 'publicado' ? 'success' : ($articulo['estado'] === 'borrador' ? 'warning' : 'default'); ?>">
                                                            <?php echo ucfirst($articulo['estado']); ?>
                                                        </span>
                                                        <?php if ($articulo['destacado']): ?>
                                                            <i class="fa fa-star text-warning"></i>
                                                        <?php endif; ?>
                                                    </small>
                                                </h4>
                                                <div style="color:#7f8c8d; margin-bottom:8px;">
                                                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($articulo['autor']); ?>
                                                    &nbsp;•&nbsp;
                                                    <i class="fa fa-folder-open"></i> <?php echo htmlspecialchars($articulo['categorias'] ?? 'Sin categoría'); ?>
                                                    &nbsp;•&nbsp;
                                                    <i class="fa fa-calendar"></i> <?php echo formatearFecha($articulo['fecha_publicacion'], 'd/m/Y'); ?>
                                                    &nbsp;•&nbsp;
                                                    <i class="fa fa-eye"></i> <?php echo number_format($articulo['visitas']); ?> visitas
                                                </div>
                                                <div class="btn-group">
                                                    <a href="<?php echo SITE_URL; ?>/templates/plantilla-articulo.php?slug=<?php echo $articulo['slug']; ?>" target="_blank" class="btn btn-xs btn-info" title="Ver"><i class="fa fa-eye"></i></a>
                                                    <a href="editar_articulo.php?id=<?php echo $articulo['id']; ?>" class="btn btn-xs btn-primary" title="Editar"><i class="fa fa-edit"></i></a>
                                                    <a href="eliminar_articulo.php?id=<?php echo $articulo['id']; ?>" class="btn btn-xs btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este artículo?');"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center">No se encontraron artículos</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" style="width: 900px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fa fa-edit"></i> Editar artículo</h4>
                            </div>
                            <div class="modal-body" style="height: 600px; padding:0;">
                                <iframe src="about:blank" style="border:0; width:100%; height:100%;"></iframe>
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
    <script>
      jQuery(function($){
        $('.btn-edit-modal').on('click', function(e){
          e.preventDefault();
          var href = $(this).attr('href');
          var $modal = $('#editModal');
          $modal.find('iframe').attr('src', href);
          $modal.modal('show');
        });
        $('#editModal').on('hidden.bs.modal', function(){
          $(this).find('iframe').attr('src', 'about:blank');
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
