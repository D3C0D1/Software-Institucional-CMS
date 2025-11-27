<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

$db = getDB();
$mensaje = getMensaje();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
    
    if ($accion === 'crear') {
        $nombre = sanitize($_POST['nombre']);
        $descripcion = sanitize($_POST['descripcion']);
        $orden = (int)$_POST['orden'];
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (!empty($nombre)) {
            $slug = createSlug($nombre);
            
            try {
                $sql = "INSERT INTO categorias (nombre, slug, descripcion, orden, activo) 
                        VALUES (:nombre, :slug, :descripcion, :orden, :activo)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':slug' => $slug,
                    ':descripcion' => $descripcion,
                    ':orden' => $orden,
                    ':activo' => $activo
                ]);
                
                setMensaje('Categoría creada exitosamente', 'success');
                header('Location: categorias.php');
                exit;
            } catch (PDOException $e) {
                setMensaje('Error al crear categoría: ' . $e->getMessage(), 'danger');
            }
        } else {
            setMensaje('El nombre de la categoría es obligatorio', 'danger');
        }
    }
    
    if ($accion === 'editar') {
        $id = (int)$_POST['id'];
        $nombre = sanitize($_POST['nombre']);
        $descripcion = sanitize($_POST['descripcion']);
        $orden = (int)$_POST['orden'];
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (!empty($nombre) && $id > 0) {
            $slug = createSlug($nombre);
            
            try {
                $sql = "UPDATE categorias SET 
                        nombre = :nombre, 
                        slug = :slug, 
                        descripcion = :descripcion, 
                        orden = :orden, 
                        activo = :activo 
                        WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':slug' => $slug,
                    ':descripcion' => $descripcion,
                    ':orden' => $orden,
                    ':activo' => $activo,
                    ':id' => $id
                ]);
                
                setMensaje('Categoría actualizada exitosamente', 'success');
                header('Location: categorias.php');
                exit;
            } catch (PDOException $e) {
                setMensaje('Error al actualizar categoría: ' . $e->getMessage(), 'danger');
            }
        }
    }
    
    if ($accion === 'eliminar') {
        $id = (int)$_POST['id'];
        
        if ($id > 0) {
            try {
                // Verificar si tiene artículos asociados
                $stmt = $db->prepare("SELECT COUNT(*) FROM articulo_categoria WHERE categoria_id = :id");
                $stmt->execute([':id' => $id]);
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    setMensaje("No se puede eliminar la categoría porque tiene {$count} artículos asociados", 'warning');
                } else {
                    $stmt = $db->prepare("DELETE FROM categorias WHERE id = :id");
                    $stmt->execute([':id' => $id]);
                    setMensaje('Categoría eliminada exitosamente', 'success');
                }
                
                header('Location: categorias.php');
                exit;
            } catch (PDOException $e) {
                setMensaje('Error al eliminar categoría: ' . $e->getMessage(), 'danger');
            }
        }
    }
}

// Obtener todas las categorías
$stmt = $db->query("SELECT c.*, COUNT(ac.articulo_id) as total_articulos 
                    FROM categorias c 
                    LEFT JOIN articulo_categoria ac ON c.id = ac.categoria_id 
                    GROUP BY c.id 
                    ORDER BY c.orden ASC, c.nombre ASC");
$categorias = $stmt->fetchAll();

// Obtener categoría para editar si se especifica
$categoria_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = (int)$_GET['editar'];
    $stmt = $db->prepare("SELECT * FROM categorias WHERE id = :id");
    $stmt->execute([':id' => $id_editar]);
    $categoria_editar = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Panel de Administración</title>
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
                    <a href="articulos.php"><i class="fa fa-list"></i> Registro de noticias</a>
                    <a href="categorias.php" class="active"><i class="fa fa-folder"></i> Categorías</a>
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
                <h1>Gestión de Categorías</h1>
                
                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $mensaje['tipo']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo htmlspecialchars($mensaje['texto']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <?php echo $categoria_editar ? 'Editar Categoría' : 'Nueva Categoría'; ?>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <form method="POST">
                                    <input type="hidden" name="accion" value="<?php echo $categoria_editar ? 'editar' : 'crear'; ?>">
                                    <?php if ($categoria_editar): ?>
                                        <input type="hidden" name="id" value="<?php echo $categoria_editar['id']; ?>">
                                    <?php endif; ?>
                                    
                                    <div class="form-group">
                                        <label for="nombre">Nombre *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nombre" 
                                               name="nombre" 
                                               value="<?php echo $categoria_editar ? htmlspecialchars($categoria_editar['nombre']) : ''; ?>"
                                               required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea class="form-control" 
                                                  id="descripcion" 
                                                  name="descripcion" 
                                                  rows="3"><?php echo $categoria_editar ? htmlspecialchars($categoria_editar['descripcion']) : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="orden">Orden</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="orden" 
                                               name="orden" 
                                               value="<?php echo $categoria_editar ? $categoria_editar['orden'] : 0; ?>">
                                        <small class="text-muted">Orden de aparición en el sitio</small>
                                    </div>
                                    
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" 
                                                   name="activo" 
                                                   value="1" 
                                                   <?php echo (!$categoria_editar || $categoria_editar['activo']) ? 'checked' : ''; ?>>
                                            Activa (visible en el sitio)
                                        </label>
                                    </div>
                                    
                                    <hr>
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fa fa-save"></i> <?php echo $categoria_editar ? 'Actualizar' : 'Crear'; ?>
                                    </button>
                                    
                                    <?php if ($categoria_editar): ?>
                                        <a href="categorias.php" class="btn btn-default btn-block">
                                            <i class="fa fa-times"></i> Cancelar
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de Categorías</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Artículos</th>
                                            <th>Orden</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($categorias)): ?>
                                            <?php foreach ($categorias as $cat): ?>
                                            <tr>
                                                <td><?php echo $cat['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($cat['nombre']); ?></strong>
                                                    <?php if ($cat['descripcion']): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($cat['descripcion']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $cat['total_articulos']; ?></span>
                                                </td>
                                                <td><?php echo $cat['orden']; ?></td>
                                                <td>
                                                    <span class="label label-<?php echo $cat['activo'] ? 'success' : 'default'; ?>">
                                                        <?php echo $cat['activo'] ? 'Activa' : 'Inactiva'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="categorias.php?editar=<?php echo $cat['id']; ?>" 
                                                       class="btn btn-xs btn-primary" 
                                                       title="Editar">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    
                                                    <?php if ($cat['total_articulos'] == 0): ?>
                                                        <form method="POST" style="display:inline;" 
                                                              onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
                                                            <input type="hidden" name="accion" value="eliminar">
                                                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                            <button type="submit" 
                                                                    class="btn btn-xs btn-danger" 
                                                                    title="Eliminar">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-xs btn-danger" 
                                                                disabled 
                                                                title="No se puede eliminar (tiene artículos asociados)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No hay categorías registradas</td>
                                            </tr>
                                        <?php endif; ?>
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
