/**
* @package Helix3 Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/


// makes sure the whole site is loaded


jQuery(window).load(function() {
    if (sp_preloader) {
        // will first fade out the loading animation
        jQuery("#preloader .load").delay(700).fadeOut();
        // will fade out the whole DIV that covers the website.
        jQuery("#preloader").delay(1000).fadeOut("slow");
    }
})


jQuery(function($) {

    $('#offcanvas-toggler').on('click', function(event){
        event.preventDefault();
        $('body').addClass('offcanvas');
    });

    $( '<div class="offcanvas-overlay"></div>' ).insertBefore( '.body-innerwrapper > .offcanvas-menu' );

    //$('.offcanvas-menu').append( '<div class="offcanvas-overlay"></div>' );

    $('.close-offcanvas, .offcanvas-overlay').on('click', function(event){
        event.preventDefault();
        $('body').removeClass('offcanvas');
    });


    //Mega Menu
    $('.sp-megamenu-wrapper').parent().parent().css('position','static').parent().css('position', 'relative');
    $('.sp-menu-full').each(function(){
        $(this).parent().addClass('menu-justify');
    });


    //wrap bottom and footer in a div
    // $("section#sp-bottom, footer#sp-footer").wrapAll('<div class="sp-bottom-footer"></div>');
    
    // has slideshow and sub header
    $(document).ready(function(){
        var spHeader = $("#sp-header");
        if ($('body.com-sppagebuilder #sp-page-builder .sppb-slider-wrapper').length) {
            $('body').addClass('has-slideshow');
        }

        //has subheader
        if ($('body #sp-page-title .sp-page-title.bg-image').length) {
             $('body').addClass('has-sub-image');
        }

        // class in header
        spHeader.addClass('menu-fixed-out');
    });

    //Slideshow height
    var slideHeight = $(window).height();
    $('.sppb-slider-wrapper.sppb-slider-fullwidth-wrapper .sppb-slideshow-fullwidth-item-bg').css('height',slideHeight);
    $('.sppb-addon-animated-headlines .sppb-addon-animated-headlines-bg').css('height',slideHeight);


    // Menu Fixed
    var windowSize = $(window);
    if ($('.sppb-slider-wrapper.sppb-slider-fullwidth-wrapper').length) {
        
        console.log($(window).scrollTop());
        if(windowSize.scrollTop() + windowSize.height() >= windowSize[0].outerHeight) {
            var stickyNavTop = $('.sppb-slider-wrapper.sppb-slider-fullwidth-wrapper').offset().top;
        }
        //alert($('.sppb-slider-wrapper.sppb-slider-fullwidth-wrapper').length);
    } else {
       //var stickyNavTop = $('#sp-header').offset().top;
    }

    // $(window).scroll(function() {
    //     if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].outerHeight) {
    //         alert('test');   
    //     } else {
    //         //$("span").hide();
    //     }
    // });

    
    if ($('body.sticky-header').length) {
        var stickyNavTop = $('#sp-header').offset().top;
        var stickyNav = function(){
            var scrollTop = $(window).scrollTop();

            if (scrollTop > stickyNavTop) {
                //alert('top');
                $('#sp-header').removeClass('menu-fixed-out')
                .addClass('menu-fixed');
            }
            else
            {
                if($('#sp-header').hasClass('menu-fixed'))
                {
                    $('#sp-header').removeClass('menu-fixed').addClass('menu-fixed-out');
                }

            }
        };

        stickyNav();

        $(window).scroll(function() {
            stickyNav();
        });
    }

    //Search
    $(".icon-search.search-icon").on('click', function(){
        $(".searchwrapper").fadeIn(200);
        $(".remove-search").delay(200).fadeIn(200);
        $(".search-icon").fadeOut(200);
    });

    $("#search_close").on('click', function(){
        $(".searchwrapper").fadeOut(200);
        $(".remove-search").fadeOut(200);
        $(".search-icon").delay(200).fadeIn(200);
    });

    // press esc to hide search
    $(document).keyup(function(e) { 
        if (e.keyCode == 27) { // esc keycode
            $(".searchwrapper").fadeOut(200);
            $(".remove-search").fadeOut(200);
            $(".search-icon").delay(200).fadeIn(200);
        }
    });

    if (sp_gotop) {
        // go to top
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut(400);
            }
        });

        $('.scrollup').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    }



    //Tooltip
    $('[data-toggle="tooltip"]').tooltip();
    
    $(document).on('click', '.sp-rating .star', function(event) {
        event.preventDefault();

        var data = {
            'action':'voting',
            'user_rating' : $(this).data('number'),
            'id' : $(this).closest('.post_rating').attr('id')
        };

        var request = {
                'option' : 'com_ajax',
                'plugin' : 'helix3',
                'data'   : data,
                'format' : 'json'
            };

        $.ajax({
            type   : 'POST',
            data   : request,
            beforeSend: function(){
                $('.post_rating .ajax-loader').show();
            },
            success: function (response) {
                var data = $.parseJSON(response.data);

                $('.post_rating .ajax-loader').hide();

                if (data.status == 'invalid') {
                    $('.post_rating .voting-result').text('You have already rated this entry!').fadeIn('fast');
                }else if(data.status == 'false'){
                    $('.post_rating .voting-result').text('Somethings wrong here, try again!').fadeIn('fast');
                }else if(data.status == 'true'){
                    var rate = data.action;
                    $('.voting-symbol').find('.star').each(function(i) {
                        if (i < rate) {
                           $( ".star" ).eq( -(i+1) ).addClass('active');
                        }
                    });

                    $('.post_rating .voting-result').text('Thank You!').fadeIn('fast');
                }

            },
            error: function(){
                $('.post_rating .ajax-loader').hide();
                $('.post_rating .voting-result').text('Failed to rate, try again!').fadeIn('fast');
            }
        });
    });

    //Slideshow angle down link
    var sppbSecondSectionId     = $('#sp-page-builder > .page-content > section:nth-child(2)').attr('id'),
    // pagebuilder second row id
    newAngleDownUrl             = '#'+sppbSecondSectionId,
    sppbSlideshowAngle          = $(".sppb-slider-wrapper .footer-animation a.slideshow-angle-down-link");

    //set url to angle down
    sppbSlideshowAngle.attr("href", newAngleDownUrl);

    // Animation after click
    var clickToSlideClasses = $(sppbSlideshowAngle);
    clickToSlideClasses.click(function(){
        $('html, body').animate({
            scrollTop: $( $.attr(this, 'href') ).offset().top
        }, 500);
        return false;
    });

    // Cargar chatbot globalmente en todas las páginas
    (function(){
        var src = 'templates/shaper_macro/js/chatbot.js';
        // Evitar cargar múltiple veces
        if([].slice.call(document.scripts).some(function(s){ return (s.src||'').indexOf(src) !== -1; })) return;
        var s = document.createElement('script');
        s.src = src; s.defer = true; s.async = true;
        document.body.appendChild(s);
    })();

    // Insertar botón PQRS en el header (mega menú y menú móvil)
    (function(){
        var pqrsHref = '../../pqrs.html';
        // Mega menú (desktop)
        var $mega = $('.sp-megamenu-parent');
        if($mega.length && $mega.find('li.menu-pqrs').length === 0){
            var $li = $('<li class="menu-pqrs"><a class="btn-pqrs" href="'+pqrsHref+'">PQRS</a></li>');
            $mega.append($li);
        }
        // Menú móvil / offcanvas
        var $slide = $('#slide-menu');
        if($slide.length && $slide.find('li.menu-pqrs').length === 0){
            var $li2 = $('<li class="menu-pqrs"><a href="'+pqrsHref+'">PQRS</a></li>');
            $slide.append($li2);
        }
    })();

    // Retardo de cierre de submenu (1s) en header aislado
    (function(){
        var $items = $('.site-nav .sp-has-child');
        if(!$items.length) return; // sólo aplica al header aislado

        $items.each(function(){
            var $item = $(this);
            var $dropdown = $item.find('> .sp-dropdown');
            if(!$dropdown.length) return;

            function openDropdown(){
                var t = $item.data('hideTimer');
                if(t) { clearTimeout(t); $item.removeData('hideTimer'); }
                $dropdown.addClass('open');
            }

            function scheduleClose(){
                var t = setTimeout(function(){
                    $dropdown.removeClass('open');
                }, 1000);
                $item.data('hideTimer', t);
            }

            $item.on('mouseenter', openDropdown);
            $item.on('mouseleave', scheduleClose);
            $dropdown.on('mouseenter', function(){
                var t = $item.data('hideTimer');
                if(t) { clearTimeout(t); $item.removeData('hideTimer'); }
            });
            $dropdown.on('mouseleave', scheduleClose);
        });
    })();

    // Retardo de cierre (1s) para submenú en preheader: "Conoce Policaribe"
    (function(){
        var $dd = $('.preheader-dropdown');
        if(!$dd.length) return;

        $dd.each(function(){
            var $wrap = $(this);
            var $submenu = $wrap.find('> .preheader-submenu');
            if(!$submenu.length) return;

            function open(){
                var t = $wrap.data('hideTimer');
                if(t) { clearTimeout(t); $wrap.removeData('hideTimer'); }
                $wrap.addClass('open');
            }

            function scheduleClose(){
                var t = setTimeout(function(){
                    $wrap.removeClass('open');
                }, 1000);
                $wrap.data('hideTimer', t);
            }

            $wrap.on('mouseenter', open);
            $wrap.on('mouseleave', scheduleClose);
            $submenu.on('mouseenter', function(){
                var t = $wrap.data('hideTimer');
                if(t) { clearTimeout(t); $wrap.removeData('hideTimer'); }
            });
            $submenu.on('mouseleave', scheduleClose);
        });
    })();

    // Overlays laterales y superior/inferior sin seguimiento de cursor
    (function(){
        try {
            var left = document.createElement('div');
            left.className = 'side-anim side-anim-left';
            var right = document.createElement('div');
            right.className = 'side-anim side-anim-right';
            var topBar = document.createElement('div');
            topBar.className = 'side-anim-top';
            var bottomBar = document.createElement('div');
            bottomBar.className = 'side-anim-bottom';
            document.body.appendChild(left);
            document.body.appendChild(right);
            document.body.appendChild(topBar);
            document.body.appendChild(bottomBar);
        } catch(err){
            console.error('Side animations init error:', err);
        }
    })();

    // Animación secuencial aleatoria de selección en tarjetas del carrusel #programas
    (function(){
        var $carousel = $('#programas');
        if(!$carousel.length) return;

        function getVisibleCards(){
            // En Owl, los elementos visibles están en .owl-item.active
            var $items = $carousel.find('.owl-item.active .course-card');
            if($items.length) return $items;
            // Fallback si aún no está inicializado como Owl
            return $carousel.find('.item-course-card .course-card');
        }

        var currentIndex = -1;
        function pickNext(){
            var $cards = getVisibleCards();
            if(!$cards.length) return null;
            var nextIdx;
            // aleatorio evitando repetir el último
            if($cards.length === 1){ nextIdx = 0; }
            else {
                do { nextIdx = Math.floor(Math.random() * $cards.length); } while(nextIdx === currentIndex);
            }
            currentIndex = nextIdx;
            return $cards.eq(nextIdx);
        }

        function tick(){
            try {
                // limpiar estado previo
                $carousel.find('.course-card.is-active').removeClass('is-active');
                var $target = pickNext();
                if($target && $target.length){ $target.addClass('is-active'); }
            } catch(err){}
        }

        // lanzar cada ~2.2s
        setInterval(tick, 2200);
        // primer disparo tras breve espera para asegurar layout
        setTimeout(tick, 800);
    })();

    // Desplazamiento continuo y suave del carrusel #programas
    (function(){
        var $carousel = $('#programas.owl-carousel');
        if(!$carousel.length) return;

        // Desactivar autoplay de Owl en este carrusel para evitar pulso
        try { $carousel.trigger('stop.owl.autoplay'); } catch(e){}

        // Obtener stage y duplicar items para bucle continuo
        function setupTicker(){
            var $stage = $carousel.find('.owl-stage');
            if(!$stage.length) return null;
            if(!$stage.data('tickerReady')){
                var $orig = $stage.children().clone(true);
                $stage.append($orig);
                $stage.data('tickerReady', true);
            }
            return $stage;
        }

        var speed = 0.25; // px por frame, ajusta para más lento/rápido
        var rafId = null;
        function runTicker(){
            var $stage = setupTicker();
            if(!$stage) return;
            var containerW = $carousel.width();
            var origW = 0;
            // calcular ancho original (antes de duplicar)
            $stage.children().each(function(i, el){
                if(i < Math.floor($stage.children().length/2)) origW += $(el).outerWidth(true);
            });

            var x = parseFloat(($stage.css('transform').match(/matrix\(.*?,.*?,.*?,.*?,(.*?),(.*?)\)/)||[0,0,0])[1]) || 0;
            function step(){
                x -= speed;
                // cuando desplazó el ancho original, recoloque para bucle sin salto
                if(Math.abs(x) >= origW){ x += origW; }
                $stage.css('transform','translate3d('+x+'px,0,0)');
                rafId = requestAnimationFrame(step);
            }
            if(!rafId) rafId = requestAnimationFrame(step);

            // pausar al pasar el cursor si se desea
            $carousel.on('mouseenter', function(){ if(rafId){ cancelAnimationFrame(rafId); rafId = null; } });
            $carousel.on('mouseleave', function(){ if(!rafId) rafId = requestAnimationFrame(step); });
        }

        // Espera a que Owl esté listo
        $carousel.on('initialized.owl.carousel', function(){ runTicker(); });
        if($carousel.data('owl.carousel')){ runTicker(); }
    })();

});