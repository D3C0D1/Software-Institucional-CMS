<?php
require_once 'config.php';
require_once 'funciones.php';

// Obtener el slug del artículo
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: blog.php');
    exit;
}

// Obtener el artículo
$articulo = obtenerArticuloPorSlug($slug);

if (!$articulo) {
    header('Location: blog.php');
    exit;
}

// Obtener artículos relacionados (misma categoría)
$articulos_relacionados = obtenerArticulos(null, 3, 0, null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <title><?php echo htmlspecialchars($articulo['titulo']); ?> - Universidad Simón Bolivar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($articulo['descripcion_corta']), 0, 160)); ?>">
    <link rel="stylesheet" type="text/css" href="https://www.unisimon.edu.co/recursos/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://www.unisimon.edu.co/recursos/css/style.css" />
    <script type="text/javascript" src="https://www.unisimon.edu.co/recursos/slick/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="https://www.unisimon.edu.co/recursos/bootstrap/js/bootstrap.min.js"></script>
    <style>
        .articulo-header {
            margin-bottom: 30px;
        }
        .articulo-imagen {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .articulo-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .articulo-contenido {
            font-size: 16px;
            line-height: 1.8;
            color: #333;
        }
        .articulo-contenido p {
            margin-bottom: 15px;
        }
        .btn-volver {
            display: inline-block;
            padding: 10px 20px;
            background-color: #21A84B;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-volver:hover {
            background-color: #1a8a3d;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row" style="margin-top: 40px;">
        <div class="col-md-9">
            <a href="blog.php" class="btn-volver">← Volver al Blog</a>
            
            <article class="articulo-header">
                <h1><?php echo htmlspecialchars($articulo['titulo']); ?></h1>
                
                <div class="articulo-meta">
                    <span><strong>Fecha:</strong> <?php echo formatearFecha($articulo['fecha_publicacion'], 'd/m/Y'); ?></span>
                    <span style="margin-left: 20px;"><strong>Autor:</strong> <?php echo htmlspecialchars($articulo['autor']); ?></span>
                    <?php if (!empty($articulo['categorias'])): ?>
                        <span style="margin-left: 20px;"><strong>Categorías:</strong> <?php echo htmlspecialchars($articulo['categorias']); ?></span>
                    <?php endif; ?>
                    <span style="margin-left: 20px;"><strong>Visitas:</strong> <?php echo number_format($articulo['visitas']); ?></span>
                </div>
                
                <?php if (!empty($articulo['imagen_principal'])): ?>
                    <img src="<?php echo htmlspecialchars($articulo['imagen_principal']); ?>" 
                         alt="<?php echo htmlspecialchars($articulo['titulo']); ?>"
                         class="articulo-imagen">
                <?php endif; ?>
                
                <div class="articulo-contenido">
                    <?php echo $articulo['contenido_completo']; ?>
                </div>
            </article>
            
            <!-- Botones de compartir redes sociales -->
            <div style="margin: 40px 0; padding: 20px; background-color: #f5f5f5; border-radius: 5px;">
                <h4>Compartir esta noticia:</h4>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/articulo.php?slug=' . $articulo['slug']); ?>" 
                   target="_blank" 
                   style="display: inline-block; padding: 10px 20px; background-color: #3b5998; color: white; text-decoration: none; margin-right: 10px; border-radius: 5px;">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/articulo.php?slug=' . $articulo['slug']); ?>&text=<?php echo urlencode($articulo['titulo']); ?>" 
                   target="_blank"
                   style="display: inline-block; padding: 10px 20px; background-color: #1da1f2; color: white; text-decoration: none; margin-right: 10px; border-radius: 5px;">
                    Twitter
                </a>
                <a href="https://wa.me/?text=<?php echo urlencode($articulo['titulo'] . ' ' . SITE_URL . '/articulo.php?slug=' . $articulo['slug']); ?>" 
                   target="_blank"
                   style="display: inline-block; padding: 10px 20px; background-color: #25d366; color: white; text-decoration: none; border-radius: 5px;">
                    WhatsApp
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px;">
                <h4>Artículos Relacionados</h4>
                <?php foreach ($articulos_relacionados as $relacionado): ?>
                    <?php if ($relacionado['id'] != $articulo['id']): ?>
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #ddd;">
                        <a href="articulo.php?slug=<?php echo $relacionado['slug']; ?>">
                            <?php if (!empty($relacionado['imagen_principal'])): ?>
                                <img src="<?php echo htmlspecialchars($relacionado['imagen_principal']); ?>" 
                                     alt="<?php echo htmlspecialchars($relacionado['titulo']); ?>"
                                     style="width: 100%; height: auto; margin-bottom: 10px;">
                            <?php endif; ?>
                            <h5 style="font-size: 14px; color: #333;">
                                <?php echo htmlspecialchars($relacionado['titulo']); ?>
                            </h5>
                            <p style="font-size: 12px; color: #666;">
                                <?php echo formatearFecha($relacionado['fecha_publicacion'], 'd/m/Y'); ?>
                            </p>
                        </a>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
