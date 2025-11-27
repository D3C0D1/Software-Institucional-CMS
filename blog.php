<?php
require_once 'config.php';
require_once 'funciones.php';
$query = [];
if (isset($_GET['categoria'])) { $query['categoria'] = (int)$_GET['categoria']; }
if (isset($_GET['busqueda'])) { $query['busqueda'] = sanitize($_GET['busqueda']); }
if (isset($_GET['pagina'])) { $query['pagina'] = (int)$_GET['pagina']; }
$qs = http_build_query($query);
header('Location: '.SITE_URL.'/templates/plantilla-galeria.php'.($qs?('?'.$qs):''));
exit;
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <title>Blog - Universidad Simón Bolivar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
    <link rel="stylesheet" type="text/css" href="https://www.unisimon.edu.co/recursos/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://www.unisimon.edu.co/recursos/css/style.css" />
    <script type="text/javascript" src="https://www.unisimon.edu.co/recursos/slick/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="https://www.unisimon.edu.co/recursos/slick/slick.min.js"></script>
    <script type="text/javascript" src="https://www.unisimon.edu.co/recursos/bootstrap/js/bootstrap.min.js"></script>
    <style>
        body {
            overflow-x: hidden;
        }
        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            max-width: 100%;
        }
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        
        /* SLIDER STYLES */
        .slider-container {
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            position: relative;
            margin-bottom: 30px;
        }
        .slider-inter {
            width: 100%;
        }
        .slide-item {
            width: 100%;
            outline: none;
        }
        .slide-content {
            width: 100%;
            max-width: 100%;
        }
        .slide-image {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 8px;
        }
        .slide-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .slide-text {
            color: white;
            text-align: center;
            padding: 20px;
            max-width: 80%;
        }
        .slide-date {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        .slide-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        .slide-btn {
            display: inline-block;
            padding: 10px 25px;
            background-color: #21A84B;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .slide-btn:hover {
            background-color: #1a8a3d;
        }
        
        /* Botones del slider */
        .slick-prev, .slick-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            cursor: pointer;
            display: flex !important;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }
        .slick-prev:hover, .slick-next:hover {
            background-color: rgba(255, 255, 255, 1);
        }
        .slick-prev {
            left: 20px;
        }
        .slick-next {
            right: 20px;
        }
        .slick-prev:before, .slick-next:before {
            color: #333;
            font-size: 20px;
        }
        
        /* BOTÓN ADMIN */
        .admin-btn-fixed {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            background-color: #21A84B;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(33, 168, 75, 0.4);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-btn-fixed:hover {
            background-color: #1a8a3d;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(33, 168, 75, 0.6);
            color: white;
            text-decoration: none;
        }
        .admin-btn-fixed i {
            font-size: 18px;
        }
        
        /* GRID DE ARTÍCULOS */
        img.img-min {
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }
        .w95, .w90, .w80 {
            max-width: 100%;
        }
        .bf3 {
            overflow-x: hidden;
        }
        [class*="col-"] {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }
        .minh135, .mh170 {
            overflow: hidden;
            border-radius: 8px;
        }
        .minh135 img, .mh170 img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .minh135:hover img, .mh170:hover img {
            transform: scale(1.05);
        }
        
        /* PAGINACIÓN */
        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
        .pagination > li {
            display: inline;
        }
        .pagination > li > a {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        .pagination > li.active > a {
            background-color: #21A84B;
            border-color: #21A84B;
            color: white;
        }
        .pagination > li > a:hover {
            background-color: #f5f5f5;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .slide-image {
                height: 300px;
            }
            .slide-title {
                font-size: 20px;
            }
            .admin-btn-fixed {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bf3">
        <div class="fright w80 w100m plp25m pad-tb40 pad-tb20m">
            <p class="c5 fz12 fw5 mb20">Filtros de noticias</p>
            
            <!-- Búsqueda -->
            <form method="GET" action="blog.php">
                <input type="search" 
                       name="busqueda" 
                       class="h32 w95 w80m boref pl30 Bbusq" 
                       placeholder="Búsqueda"
                       value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
            </form>
            
            <!-- Categorías -->
            <div class="mt20">
                <?php foreach ($categorias as $cat): ?>
                    <a href="blog.php?categoria=<?php echo $cat['id']; ?>">
                        <div class="pad20 dnonem c6 fz10 borc5 poi catnoticias mb10 <?php echo ($categoria_id == $cat['id']) ? 'activo' : ''; ?>" 
                             style="<?php echo ($categoria_id == $cat['id']) ? 'background-color: #f0f0f0;' : ''; ?>">
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </div>
                    </a>
                <?php endforeach; ?>
                
                <?php if ($categoria_id || $busqueda): ?>
                    <a href="blog.php">
                        <div class="pad20 dnonem c6 fz10 borc5 poi catnoticias mb10" style="background-color: #e8f5e9;">
                            Ver todas las noticias
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Información adicional -->
            <div class="mt40 dnonem">
                <div class="mt60 pad-tb10 bor80 w90" style="border-right: 0;border-left: 0">
                    <div class="fleft"><span class="icon-video-05 c3 fz24"></span></div>
                    <div class="fleft ml10 lh18 uper fz9 c3 lts1">visita nuestro canal<br>de Youtube</div>
                    <div class="clear"></div>
                </div>
                <div class="w90 mt30 pad10 bdf">
                    <table class="w100 collapse">
                        <tr>
                            <td class="bwhite pt10 c6 fw5 tcenter lts2 pb5">ALIANZA</td>
                        </tr>
                        <tr>
                            <td class="pad-lr10 pb15 tcenter bwhite">
                                <a href="https://www.unisimon.edu.co/servicios/alianzaelheraldo">
                                    <img src="https://www.unisimon.edu.co/recursos/img/el-heraldo-06.jpg" class="imgT" alt="el heraldo">
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="w90 mt30">
                    <div class="w100 plp5 mb20">
                        <span class="icon-iconos-40 fz18 c29a mid"></span> 
                        <span class="ml15 fz15 c4d fw3">#Unisimon</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9 plp4">
        <div class="w95 pad-tb40" id="result">
            
            <!-- Slider de artículos destacados -->
            <?php if (!empty($articulos_destacados)): ?>
            <div class="slider-container mb30">
                <div class="slider-inter">
                    <?php foreach ($articulos_destacados as $destacado): ?>
                    <div class="slide-item">
                        <a href="articulo.php?slug=<?php echo $destacado['slug']; ?>" style="text-decoration: none;">
                            <div class="slide-content">
                                <div class="slide-image">
                                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($destacado['imagen_principal']); ?>" 
                                         alt="<?php echo htmlspecialchars($destacado['titulo']); ?>">
                                    <div class="slide-overlay">
                                        <div class="slide-text">
                                            <p class="slide-date"><?php echo formatearFecha($destacado['fecha_publicacion'], 'd F Y'); ?></p>
                                            <h3 class="slide-title"><?php echo htmlspecialchars($destacado['titulo']); ?></h3>
                                            <span class="slide-btn">Ver Noticia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Título de sección -->
            <div class="c4d fz15 fw3 pb5 bor92 mb30">
                <?php if ($categoria_actual): ?>
                    Noticias de <?php echo htmlspecialchars($categoria_actual['nombre']); ?>
                <?php elseif ($busqueda): ?>
                    Resultados para "<?php echo htmlspecialchars($busqueda); ?>"
                <?php else: ?>
                    Noticias Recientes
                <?php endif; ?>
            </div>
            
            <!-- Grid de artículos -->
            <div class="row">
                <?php if (!empty($articulos)): ?>
                    <?php foreach ($articulos as $articulo): ?>
                    <div class="col-md-4 mb30">
                        <a href="articulo.php?slug=<?php echo $articulo['slug']; ?>">
                            <div class="w95">
                                <div class="minh135 mh170 w100 rel hid">
                                    <img src="<?php echo htmlspecialchars($articulo['imagen_principal']); ?>" 
                                         class="img-min" 
                                         alt="<?php echo htmlspecialchars($articulo['titulo']); ?>">
                                </div>
                                <p class="c6 tita mb5 fz10 mt10">
                                    <?php echo formatearFecha($articulo['fecha_publicacion'], 'F d, Y'); ?>
                                </p>
                                <p class="c3 mb5 fz12">
                                    <?php echo htmlspecialchars($articulo['titulo']); ?>
                                </p>
                                <p class="c6 mb10 fz11">
                                    <?php echo substr(strip_tags($articulo['descripcion_corta']), 0, 100); ?>...
                                </p>
                                <span class="cverde fw5">Ver noticia</span>
                            </div>
                        </a>
                    </div>
                    <?php if (($loop_index ?? 0) % 3 == 2): ?>
                        <div class="clear"></div>
                    <?php endif; ?>
                    <?php $loop_index = ($loop_index ?? 0) + 1; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12">
                        <p class="text-center">No se encontraron noticias.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
            <div class="row mt40">
                <div class="col-md-12 text-center">
                    <ul class="pagination">
                        <?php if ($pagina > 1): ?>
                            <li><a href="?pagina=<?php echo $pagina-1; ?><?php echo $categoria_id ? '&categoria='.$categoria_id : ''; ?><?php echo $busqueda ? '&busqueda='.urlencode($busqueda) : ''; ?>">« Anterior</a></li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="<?php echo ($i == $pagina) ? 'active' : ''; ?>">
                                <a href="?pagina=<?php echo $i; ?><?php echo $categoria_id ? '&categoria='.$categoria_id : ''; ?><?php echo $busqueda ? '&busqueda='.urlencode($busqueda) : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagina < $total_paginas): ?>
                            <li><a href="?pagina=<?php echo $pagina+1; ?><?php echo $categoria_id ? '&categoria='.$categoria_id : ''; ?><?php echo $busqueda ? '&busqueda='.urlencode($busqueda) : ''; ?>">Siguiente »</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Multimedia Section -->
            <div class="row mb40 mt40">
                <p class="c3 fz16 fw5 mb10"><span class="icon-iconos-37 fz19 mid caz mr5"></span> Multimedia</p>
                <div class="col-md-6 pr10 pad0m">
                    <a href="https://www.unisimon.edu.co/eventos/">
                        <div class="w100 h330 hid rel">
                            <img src="https://www.unisimon.edu.co/showimagen/showimage/universidad-simon-bolivar-5c032.jpg" class="img-min" alt="unisimon multimedia">
                            <div class="abs w100 h100p zind2 bnegro top0">
                                <div class="ctexto"><p class="tcenter cwhite fz15 lts1">Registro de Eventos</p></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="https://www.unisimon.edu.co/servicios/multimedia">
                        <div class="w100 h162 rel hid">
                            <img src="https://www.unisimon.edu.co/showimagen/showimage/universidad-simon-bolivar-06f7d.jpg" class="img-min" alt="unisimon multimedia">
                            <div class="abs w100 h100p zind2 bnegro top0">
                                <div class="ctexto"><p class="tcenter cwhite fz15 lts1">Galería Fotográfica</p></div>
                            </div>
                        </div>
                    </a>
                    <a target="_blank" href="https://www.youtube.com/user/videosunisimon">
                        <div class="w100 h162 rel hid mt6 mt0m">
                            <img src="https://www.unisimon.edu.co/showimagen/showimage/universidad-simon-bolivar-1c3d3.jpg" class="img-min" alt="unisimon multimedia">
                            <div class="abs w100 h100p zind2 bnegro top0">
                                <div class="ctexto"><p class="tcenter cwhite fz15 lts1">Canal de Youtube</p></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 pl10 pad0m">
                    <a href="https://www.unisimon.edu.co/servicios/multimedia">
                        <div class="w100 h330 rel hid">
                            <img src="https://www.unisimon.edu.co/showimagen/showimage/universidad-simon-bolivar-7e294n.jpg" class="img-min" alt="unisimon multimedia">
                            <div class="abs w100 h100p zind2 bnegro top0">
                                <div class="ctexto"><p class="tcenter cwhite fz15 lts1">Historia Gráfica</p></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Botón flotante para ir al admin -->
<a href="admin/login.php" class="admin-btn-fixed" title="Panel de Administración">
    <i class="glyphicon glyphicon-cog"></i>
    <span>Admin</span>
</a>

<script>
jQuery(document).ready(function ($) {
    $(".slider-inter").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: true,
        dots: true,
        fade: true,
        cssEase: 'linear',
        adaptiveHeight: false
    });
});
</script>

</body>
</html>
