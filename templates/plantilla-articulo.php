<?php
require_once '../config.php';
require_once '../funciones.php';
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
if (empty($slug)) { header('Location: '.SITE_URL.'/templates/plantilla-galeria.php'); exit; }
$articulo = obtenerArticuloPorSlug($slug);
if (!$articulo) { header('Location: '.SITE_URL.'/templates/plantilla-galeria.php'); exit; }
$todasCategorias = isset($articulo['categorias']) ? $articulo['categorias'] : '';
$url_articulo = SITE_URL.'/templates/plantilla-articulo.php?slug='.$slug;
$contenidoPlano = trim(strip_tags($articulo['contenido_completo']));
$palabras = preg_match_all('/[A-Za-zÁÉÍÓÚÜáéíóúüÑñ]+/u', $contenidoPlano, $m);
$tiempoLectura = max(1, ceil($palabras / 250));
$categoriasArr = is_array($todasCategorias) ? $todasCategorias : array_filter(array_map('trim', explode(',', (string)$todasCategorias)));
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="es-co">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($articulo['titulo']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <link href="https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/magnific-popup.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/simple-line-icons@2.4.1/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
    <link href="/templates/sj_thedaily/asset/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/templates/sj_thedaily/css/template-green.css" rel="stylesheet" type="text/css" />
    <link href="/templates/sj_thedaily/css/pattern.css" rel="stylesheet" type="text/css" />
    <link href="/templates/sj_thedaily/css/jquery.mmenu.all.css" rel="stylesheet" type="text/css" />
    <link href="/templates/sj_thedaily/asset/fonts/awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/templates/sj_thedaily/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body{background:#f5f7fa;color:#222}
        .container{max-width:1180px;margin:0 auto;padding:0 24px}
        #yt_header, #topbar, #searching, #megame{display:none !important}
        #yt_footer, #yt-off-resmenu, .backtotop, section.block.bg-white{display:none !important}
        .itemDate{margin-top:8px;color:#5a6c7e;text-transform:uppercase;letter-spacing:.08em;text-align:left;font-size:13px}
        .itemTitle{margin:10px 0 6px;text-align:left;font-weight:800;font-size:56px;line-height:1.1;color:#000000}
        .badge-slogan{display:inline-block;background:#7a1510;color:#fff;font-weight:700;font-size:12px;padding:8px 12px;border-radius:12px;margin-left:12px;letter-spacing:.06em}
        .article-header{padding-top:28px;padding-bottom:18px;border-bottom:1px solid #e2e8f0}
        .breadcrumbs{font-size:13px;color:#6b7f95}
        .breadcrumbs a{color:#7a1510;text-decoration:none}
        .breadcrumbs a:hover{text-decoration:underline}
        .meta-top{display:flex;gap:16px;align-items:center;margin-top:10px}
        .meta-dot{width:4px;height:4px;background:#b0bcc9;border-radius:2px}
        .meta-categories{margin-top:10px;display:flex;flex-wrap:wrap;gap:10px}
        .chip{display:inline-flex;align-items:center;font-size:12px;line-height:1;color:#7a1510;border:1px solid #cfd8e3;border-radius:999px;padding:8px 12px;background:#fff}
        #hero-carousel{position:relative;margin:24px auto;max-width:1100px;background:#eef2f7;border-radius:16px;overflow:hidden}
        #hero-carousel .item{position:relative}
        .hg-img{width:100%;height:540px;object-fit:cover;opacity:.38;transition:opacity .3s ease, transform .3s ease}
        #hero-carousel .owl-item.center .hg-img{opacity:1;transform:scale(1.03)}
        #hero-carousel .owl-nav{position:absolute;top:50%;left:0;right:0;transform:translateY(-50%);display:flex;justify-content:space-between;padding:0 12px}
        #hero-carousel .owl-nav .owl-prev,#hero-carousel .owl-nav .owl-next{width:44px;height:44px;background:rgba(122,21,16,.6);color:#fff;border-radius:22px;line-height:44px;text-align:center}
        .itemBody{margin-top:10px}
        .itemFullText{font-size:18px;line-height:1.9;color:#222;text-align:justify;text-align-last:left;hyphens:auto}
        .itemFullText p{text-align:justify;text-align-last:left}
        .itemFullText p:first-of-type::first-letter{font-weight:800;font-size:54px;float:left;line-height:.86;margin:6px 12px 0 0;color:#7a1510}
        .itemFullText img{max-width:100%;height:auto;border-radius:12px;box-shadow:0 10px 24px rgba(0,0,0,.06);margin:16px 0}
        .itemFullText blockquote{border-left:4px solid #7a1510;margin:24px 0;padding:8px 16px;color:#7a1510;font-weight:600;background:#f7efef;border-radius:8px}
        .aside{position:relative}
        .meta-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;box-shadow:0 10px 24px rgba(14,42,71,.06)}
        .meta-card-title{font-size:14px;font-weight:700;color:#7a1510;margin-bottom:16px;text-transform:uppercase;letter-spacing:.08em}
        .fact{display:flex;gap:8px;align-items:center;margin-bottom:8px;color:#4a5b6b;font-size:14px}
        .fact i{color:#7a1510}
        .share-bar{display:flex;gap:12px;flex-wrap:wrap}
        .share-bar a{display:inline-flex;align-items:center;justify-content:center;width:46px;height:46px;border-radius:23px;background:#7a1510;color:#fff;text-decoration:none}
        .share-bar a i{font-size:18px}
        .share-bar a.facebook{background:#1877f2}
        .share-bar a.instagram{background:#E4405F}
        .share-bar a.youtube{background:#FF0000}
        @media (max-width:992px){.itemTitle{font-size:40px}.hg-img{height:420px}}
        @media (max-width:640px){.itemTitle{font-size:30px}.hg-img{height:300px}}
    </style>
</head>
<body id="bd">
    <section id="content" class="block">
        <div class="row">
            <div id="content_main">
                <div class="content-main-inner">
                    <div id="k2Container" class="noticias itemView">
                        <div class="container">
                            <div class="article-header">
                                <div class="breadcrumbs"><a href="<?php echo SITE_URL; ?>/templates/plantilla-galeria.php">Noticias</a><?php if (!empty($categoriasArr)): $primeraCategoria = reset($categoriasArr); ?> &nbsp;›&nbsp;<a href="<?php echo SITE_URL; ?>/templates/plantilla-galeria.php?categoria=<?php echo urlencode($primeraCategoria); ?>"><?php echo htmlspecialchars($primeraCategoria); ?></a><?php endif; ?></div>
                                <div class="meta-top"><div class="itemDate"><?php echo formatearFecha($articulo['fecha_publicacion'], 'l, d Y F'); ?></div><div class="meta-dot"></div><div class="itemReading"><?php echo $tiempoLectura; ?> min de lectura</div><span class="badge-slogan">NOTICIAS CORPORATIVAS</span></div>
                                <h1 class="itemTitle"><?php echo htmlspecialchars($articulo['titulo']); ?></h1>
                                <?php if (!empty($categoriasArr)): ?>
                                <div class="meta-categories">
                                    <?php foreach($categoriasArr as $cat): ?>
                                        <a href="<?php echo SITE_URL; ?>/templates/plantilla-galeria.php?categoria=<?php echo urlencode($cat); ?>" class="chip"><?php echo htmlspecialchars($cat); ?></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php
                          function site_root(){ return realpath(__DIR__ . '/..'); }
                          function exists_rel($rel){ return file_exists(site_root() . '/' . $rel); }
                          function find_img_basename($basename){
                            $base = site_root() . '/policaribe/images';
                            if (!is_dir($base)) return '';
                            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));
                            foreach ($it as $f) {
                              if ($f->isFile() && strtolower($f->getFilename()) === strtolower($basename)) {
                                $full = $f->getPathname();
                                $rel = trim(substr($full, strlen(site_root())+1), '/');
                                $parts = explode('/', $rel);
                                return implode('/', array_map('rawurlencode',$parts));
                              }
                            }
                            return '';
                          }
                          function norm_src($s){
                            $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
                            $s = rawurldecode($s);
                            if (strpos($s, '../images/') === 0) { $s = 'policaribe/' . ltrim(str_replace('../','',$s), '/'); }
                            elseif (strpos($s, 'images/') === 0) { $s = 'policaribe/' . $s; }
                            $parts = explode('/', $s);
                            $s = implode('/', array_map('rawurlencode', $parts));
                            if (!exists_rel($s)) {
                              $base = basename($s);
                              $found = find_img_basename($base);
                              if ($found) $s = $found;
                            }
                            if (!preg_match('/^https?:\/\//i', $s)) {
                              if (strpos($s, '/') === 0) {
                                $s = SITE_URL . $s;
                              } else {
                                $s = SITE_URL . '/' . $s;
                              }
                            }
                            return $s;
                          }
                          function fix_body_imgs($html){
                            return preg_replace_callback('/(<img[^>]+src=["\'])([^"\']+)(["\'])/i', function($m){
                              return $m[1] . norm_src($m[2]) . $m[3];
                            }, $html);
                          }
                          function remove_imgs($html){
                            $html = preg_replace('/<img[^>]*>/i','',$html);
                            $html = preg_replace('/<figure[^>]*>\s*<\/figure>/i','',$html);
                            $html = preg_replace('/<a[^>]*>\s*<\/a>/i','',$html);
                            return $html;
                          }
                          $galeria = [];
                          if (!empty($articulo['imagen_principal'])) { $galeria[] = norm_src($articulo['imagen_principal']); }
                          if (!empty($articulo['contenido_completo'])) {
                            if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $articulo['contenido_completo'], $m)) {
                              foreach ($m[1] as $src) {
                                $src2 = norm_src($src);
                                if (!in_array($src2, $galeria)) { $galeria[] = $src2; }
                              }
                            }
                            if (preg_match_all('/<a[^>]+href=["\']([^"\']+\.(?:jpe?g|png|gif|webp|svg|avif))["\'][^>]*>/i', $articulo['contenido_completo'], $m2)) {
                              foreach ($m2[1] as $href) {
                                $src3 = norm_src($href);
                                if (!in_array($src3, $galeria)) { $galeria[] = $src3; }
                              }
                            }
                          }
                        ?>
                        <?php if (!empty($galeria)): ?>
                        <div id="hero-carousel" class="owl-carousel">
                            <?php foreach($galeria as $src): ?>
                                <div class="item"><img class="hg-img" src="<?php echo htmlspecialchars($src); ?>" alt=""></div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="itemBody">
                            <div class="container">
                                <div class="row g-5">
                                    <div class="col-lg-8">
                                        <div class="itemFullText">
                                            <?php echo remove_imgs(fix_body_imgs($articulo['contenido_completo'])); ?>
                                        </div>
                                    </div>
                                    <aside class="col-lg-4 aside">
                                        <div class="meta-card">
                                            <div class="meta-card-title">Compartir</div>
                                            <div class="share-bar">
                                                <a class="facebook" href="https://www.facebook.com/policaribe" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                                                <a class="instagram" href="https://www.instagram.com/policaribe_oficial/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                                                <a class="youtube" href="https://www.youtube.com/@policaribe" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a>
                                            </div>
                                            <div class="meta-card-title" style="margin-top:24px">Detalles</div>
                                            <div class="fact"><i class="fa fa-calendar"></i><span><?php echo formatearFecha($articulo['fecha_publicacion'], 'l, d Y F'); ?></span></div>
                                            <?php if (!empty($categoriasArr)): ?>
                                            <div class="fact"><i class="fa fa-folder-open"></i><span><?php echo htmlspecialchars(implode(', ', $categoriasArr)); ?></span></div>
                                            <?php endif; ?>
                                            <div class="fact"><i class="fa fa-clock-o"></i><span><?php echo $tiempoLectura; ?> min de lectura</span></div>
                                        </div>
                                    </aside>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="/templates/sj_thedaily/asset/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
      jQuery(function($){
        var $owl = $('#hero-carousel');
        if(!$owl.length) return;
        function init(){
          if(!$owl.length || !$owl.owlCarousel) return;
          if($owl.data('owl-initialized')) return;
          $owl.data('owl-initialized', true);
          $owl.owlCarousel({
            items:1,
            center:true,
            loop:true,
            margin:24,
            stagePadding:240,
            dots:false,
            autoplay:true,
            autoplayTimeout:5000,
            nav:true,
            navText:['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
            responsive:{
              0:{items:1, stagePadding:80},
              768:{items:1, stagePadding:160},
              1200:{items:1, stagePadding:240}
            }
          });
          $owl.on('changed.owl.carousel', function(){
            var $center = $owl.find('.owl-item.center .hg-img');
            $owl.find('.hg-img').removeClass('animate__animated animate__fadeIn');
            $center.addClass('animate__animated animate__fadeIn');
          });
        }
        if($.fn && $.fn.owlCarousel){
          init();
        } else {
          var s = document.createElement('script');
          s.src = 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';
          s.onload = init;
          document.body.appendChild(s);
        }
      });
    </script>
</body>
</html>