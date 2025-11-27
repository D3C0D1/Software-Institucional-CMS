<?php
require_once '../config.php';
require_once '../funciones.php';
requerirLogin();

$db = getDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: articulos.php');
    exit;
}

// Obtener artículo
$stmt = $db->prepare("SELECT * FROM articulos WHERE id = :id");
$stmt->execute([':id' => $id]);
$articulo = $stmt->fetch();

if (!$articulo) {
    setMensaje('Artículo no encontrado', 'danger');
    header('Location: articulos.php');
    exit;
}

// Obtener categorías del artículo
$stmt = $db->prepare("SELECT categoria_id FROM articulo_categoria WHERE articulo_id = :id");
$stmt->execute([':id' => $id]);
$categorias_articulo = $stmt->fetchAll(PDO::FETCH_COLUMN);

$errores = [];
$categorias = obtenerCategorias();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = sanitize($_POST['titulo']);
    $descripcion_corta = sanitize($_POST['descripcion_corta']);
    $contenido_completo = $_POST['contenido_completo'];
    $autor = sanitize($_POST['autor']);
    $fecha_publicacion = sanitize($_POST['fecha_publicacion']);
    $estado = sanitize($_POST['estado']);
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $categorias_seleccionadas = isset($_POST['categorias']) ? $_POST['categorias'] : [];

    // Validaciones
    if (empty($titulo)) $errores[] = "El título es obligatorio";
    if (empty($descripcion_corta)) $errores[] = "La descripción corta es obligatoria";
    if (empty($contenido_completo)) $errores[] = "El contenido completo es obligatorio";
    if (empty($autor)) $errores[] = "El autor es obligatorio";
    if (empty($fecha_publicacion)) $errores[] = "La fecha de publicación es obligatoria";
    if (empty($categorias_seleccionadas)) $errores[] = "Debe seleccionar al menos una categoría";

    // Procesar imagen si se subió una nueva
    $imagen_principal = $articulo['imagen_principal'];
    if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === 0) {
        $resultado = subirImagen($_FILES['imagen_principal']);
        if ($resultado['exito']) {
            // Eliminar imagen anterior
            if (file_exists('../' . $articulo['imagen_principal'])) {
                @unlink('../' . $articulo['imagen_principal']);
            }
            $imagen_principal = $resultado['ruta'];
        } else {
            $errores[] = $resultado['error'];
        }
    }

    if ($destacado && $estado === 'publicado') {
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM articulos WHERE destacado = 1 AND estado = 'publicado' AND id != :id");
            $stmt->execute([':id' => $id]);
            $count = (int)$stmt->fetchColumn();
            if ($count >= 3) { $errores[] = "No se puede marcar como destacado: ya hay 3 publicaciones destacadas. Desmarque otra primero."; }
        } catch (Exception $e) {
            $errores[] = "Error al validar destacado";
        }
    }

    if (empty($errores)) {
        try {
            $db->beginTransaction();

            // Crear slug si el título cambió
            $slug = $articulo['slug'];
            if ($titulo !== $articulo['titulo']) {
                $slug = createSlug($titulo);
                
                // Verificar si el slug ya existe (excluyendo el artículo actual)
                $stmt = $db->prepare("SELECT COUNT(*) FROM articulos WHERE slug = :slug AND id != :id");
                $stmt->execute([':slug' => $slug, ':id' => $id]);
                if ($stmt->fetchColumn() > 0) {
                    $slug = $slug . '-' . time();
                }
            }

            // Actualizar artículo
            $sql = "UPDATE articulos SET 
                    titulo = :titulo, 
                    slug = :slug, 
                    descripcion_corta = :descripcion_corta, 
                    contenido_completo = :contenido_completo, 
                    imagen_principal = :imagen_principal, 
                    autor = :autor, 
                    fecha_publicacion = :fecha_publicacion, 
                    estado = :estado, 
                    destacado = :destacado
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':slug' => $slug,
                ':descripcion_corta' => $descripcion_corta,
                ':contenido_completo' => $contenido_completo,
                ':imagen_principal' => $imagen_principal,
                ':autor' => $autor,
                ':fecha_publicacion' => $fecha_publicacion,
                ':estado' => $estado,
                ':destacado' => $destacado,
                ':id' => $id
            ]);

            // Actualizar categorías
            $db->prepare("DELETE FROM articulo_categoria WHERE articulo_id = :id")->execute([':id' => $id]);
            
            $sql = "INSERT INTO articulo_categoria (articulo_id, categoria_id) VALUES (:articulo_id, :categoria_id)";
            $stmt = $db->prepare($sql);
            foreach ($categorias_seleccionadas as $categoria_id) {
                $stmt->execute([
                    ':articulo_id' => $id,
                    ':categoria_id' => $categoria_id
                ]);
            }

            $db->commit();

            setMensaje('Artículo actualizado exitosamente', 'success');
            header('Location: articulos.php');
            exit;

        } catch (PDOException $e) {
            $db->rollBack();
            $errores[] = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        // Si hay errores, actualizar las categorías seleccionadas para el formulario
        $categorias_articulo = $categorias_seleccionadas;
    }
}

// Convertir fecha para el input datetime-local
$fecha_input = date('Y-m-d\TH:i', strtotime($articulo['fecha_publicacion']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo - Panel de Administración</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
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
        .form-group label {
            font-weight: bold;
        }
        .checkbox-group {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
        .imagen-actual {
            max-width: 300px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .tox-notifications-container, .tox-notification{display:none !important}
        .sidebar-toggle{color:#fff;text-transform:uppercase;font-size:12px;padding:10px 20px;cursor:pointer;display:flex;align-items:center;justify-content:space-between}
        .sidebar-section{padding:0}
        .sidebar-section.collapsed{display:none}
        .sidebar-toggle .chevron{transition:transform .2s ease}
        .sidebar-toggle.collapsed .chevron{transform:rotate(-90deg)}
        .sidebar-toggle span i{margin-right:8px}
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
                    <a href="crear_articulo.php" class="active"><i class="fa fa-plus"></i> Crear noticias</a>
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
                <h1>Editar Artículo</h1>
                
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <strong>Errores:</strong>
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="titulo">Título *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="titulo" 
                                               name="titulo" 
                                               value="<?php echo htmlspecialchars($articulo['titulo']); ?>"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label for="descripcion_corta">Descripción Corta *</label>
                                        <textarea class="form-control" 
                                                  id="descripcion_corta" 
                                                  name="descripcion_corta" 
                                                  rows="3" 
                                                  required><?php echo htmlspecialchars($articulo['descripcion_corta']); ?></textarea>
                                        <small class="text-muted">Resumen breve del artículo (máx. 200 caracteres)</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="contenido_completo">Contenido Completo *</label>
                                        <textarea id="contenido_completo" 
                                                  name="contenido_completo"><?php echo htmlspecialchars($articulo['contenido_completo']); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="autor">Autor *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="autor" 
                                               name="autor" 
                                               value="<?php echo htmlspecialchars($articulo['autor']); ?>"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label for="fecha_publicacion">Fecha de Publicación *</label>
                                        <input type="datetime-local" 
                                               class="form-control" 
                                               id="fecha_publicacion" 
                                               name="fecha_publicacion" 
                                               value="<?php echo $fecha_input; ?>"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label for="estado">Estado *</label>
                                        <select class="form-control" id="estado" name="estado" required>
                                            <option value="borrador" <?php echo $articulo['estado'] === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                                            <option value="publicado" <?php echo $articulo['estado'] === 'publicado' ? 'selected' : ''; ?>>Publicado</option>
                                            <option value="archivado" <?php echo $articulo['estado'] === 'archivado' ? 'selected' : ''; ?>>Archivado</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="imagen_principal">Imagen Principal</label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="imagen_principal" 
                                               name="imagen_principal" 
                                               accept="image/*">
                                        <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                                        
                                        <?php if ($articulo['imagen_principal']): ?>
                                            <div>
                                                <label>Imagen actual:</label><br>
                                                <img src="<?php echo SITE_URL . '/' . $articulo['imagen_principal']; ?>" 
                                                     alt="Imagen actual" 
                                                     class="imagen-actual">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" 
                                                       name="destacado" 
                                                       value="1" 
                                                       <?php echo $articulo['destacado'] ? 'checked' : ''; ?>>
                                                <strong>Artículo Destacado</strong>
                                            </label>
                                            <br><small class="text-muted">Aparecerá en el slider principal</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Categorías * <small>(Seleccione al menos una)</small></label>
                                        <div class="checkbox-group">
                                            <?php foreach ($categorias as $cat): ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" 
                                                               name="categorias[]" 
                                                               value="<?php echo $cat['id']; ?>"
                                                               <?php echo in_array($cat['id'], $categorias_articulo) ? 'checked' : ''; ?>>
                                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Actualizar Artículo
                                </button>
                                <a href="articulos.php" class="btn btn-default">
                                    <i class="fa fa-times"></i> Cancelar
                                </a>
                                <a href="<?php echo SITE_URL; ?>/articulo.php?slug=<?php echo $articulo['slug']; ?>" 
                                   target="_blank" 
                                   class="btn btn-info">
                                    <i class="fa fa-eye"></i> Ver Artículo
                                </a>
                            </div>
                        </form>
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
        window.tinymce && (tinymce.baseURL = 'https://cdn.jsdelivr.net/npm/tinymce@6');
        tinymce.init({
            selector: '#contenido_completo',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }',
            branding: false,
            promotion: false
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
