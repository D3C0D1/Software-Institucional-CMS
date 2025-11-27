document.addEventListener('DOMContentLoaded', function(){
  var ml = document.getElementById('ml-menu');
  var wrap = ml ? ml.querySelector('.menu__wrap') : null;
  var main = ml ? ml.querySelector('ul[data-menu="main"]') : null;
  var subProg = ml ? ml.querySelector('ul[data-menu="submenu-programas"]') : null;
  if(ml && wrap && main){
    var hdr = document.createElement('div');
    hdr.className = 'menu-mobile-header';
    var backBtn = document.createElement('button');
    backBtn.className = 'btn-back';
    backBtn.textContent = '← Regresar';
    backBtn.style.display = 'none';
    hdr.appendChild(backBtn);
    wrap.insertBefore(hdr, wrap.firstChild);
    function showMenu(level){
      [main, subProg].forEach(function(ul){ if(ul){ ul.classList.remove('active'); }});
      if(level){ level.classList.add('active'); }
      backBtn.style.display = (level === subProg) ? 'inline-block' : 'none';
    }
    showMenu(main);
    var progLink = main.querySelector('[data-submenu="submenu-programas"]');
    if(progLink){ progLink.addEventListener('click', function(e){ e.preventDefault(); showMenu(subProg); }); }
    backBtn.addEventListener('click', function(e){ e.preventDefault(); showMenu(main); });
    var headerHamb = document.getElementById('header-hamburger');
    var preHamb = document.getElementById('preheader-hamburger');
    function openMenu(){ ml.classList.add('show'); showMenu(main); }
    function closeMenu(){ ml.classList.remove('show'); showMenu(main); }
    if(headerHamb){ headerHamb.addEventListener('click', function(e){ e.preventDefault(); openMenu(); }); }
    if(preHamb){ preHamb.addEventListener('click', function(e){ e.preventDefault(); openMenu(); }); }
    var closeBtn = ml.querySelector('.action--close');
    if(closeBtn){ closeBtn.addEventListener('click', function(e){ e.preventDefault(); closeMenu(); }); }
  }
});

document.addEventListener('DOMContentLoaded', function(){
  try {
    var heroSwiper = new Swiper('.heroSwiper', {
      slidesPerView: 1,
      spaceBetween: 0,
      loop: true,
      autoplay: { delay: 4000, disableOnInteraction: false },
      speed: 800,
      effect: 'fade',
      fadeEffect: { crossFade: true }
    });
    var prevBtn = document.querySelector('.fizq.swiper-button-prev-custom');
    var nextBtn = document.querySelector('.fder.swiper-button-next-custom');
    if(prevBtn){ prevBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); heroSwiper.slidePrev(); }); }
    if(nextBtn){ nextBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); heroSwiper.slideNext(); }); }
  } catch(e) {}
});

document.addEventListener('DOMContentLoaded', function(){
  try {
    if(window.innerWidth <= 583){
      new Swiper('.heroSwiperMobile', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        speed: 800,
        effect: 'fade',
        fadeEffect: { crossFade: true }
      });
    }
  } catch(e) {}
});

if(typeof jQuery !== 'undefined'){
  try{ jQuery.noConflict(); }catch(e){}
  jQuery(function($){
    $('a[href^="#"]').on('click', function(e){
      var target = $(this.getAttribute('href'));
      if(target.length){ e.preventDefault(); $('html, body').stop().animate({ scrollTop: target.offset().top - 120 }, 1000); }
    });
  });
}

if(typeof jQuery !== 'undefined'){
  jQuery(function($){
    var SITE_URL = (window.location.hostname === 'localhost'
      ? (window.location.origin + '/Software-Institucional-CMS')
      : 'https://policaribe.edu.co');
    function normSrc(s){
      if(!s) return 'images/demo/blog/1.jpg';
      s = (s||'').trim();
      if(/^https?:\/\//i.test(s) || s.indexOf('data:') === 0) return s;
      if(s.charAt(0) === '/') return SITE_URL + s;
      return SITE_URL + '/' + s.replace(/\s/g, '%20');
    }
    function cargarNoticias(){
      $.getJSON('api/articulos.php', { type: 'news', limit: 3 }, function(resp){
        var $container = $('#noticias-vertical').empty();
        if(resp && resp.data && resp.data.length){
          resp.data.forEach(function(a){
            var href = 'templates/plantilla-articulo.php?slug=' + encodeURIComponent(a.slug);
            var html = '<div class="row mb20">'
              + '<a href="' + href + '">' 
              +   '<div class="col-md-7">' 
              +     '<div class="img-wrap">' 
              +       '<img data-src="' + normSrc(a.imagen_principal) + '" alt="' + (a.titulo || 'noticia') + '" onerror="fallbackImg(this, buildCandidates(this.getAttribute(\'data-src\')))">' 
              +     '</div>' 
              +   '</div>' 
              +   '<div class="col-md-5 pl15">' 
              +     '<p class="c6 tita mb5 fz12 mt10">' + a.fecha + '</p>' 
              +     '<p class="c3 mb5 fz12">' + a.titulo + '</p>' 
              +     '<p class="c6 mb10 fz11">' + (a.descripcion_corta || '') + '</p>' 
              +     '<span class="cverde fw5">Ver noticia</span>' 
              +   '</div>' 
              +   '<div class="clear"></div>' 
              + '</a>';
            $container.append(html);
            $container.find('img[data-src]:last').each(function(){
              var candidates = buildCandidates(this.getAttribute('data-src'));
              fallbackImg(this, candidates);
            });
          });
        } else {
          $container.html('<p class="c6 fz12">No hay noticias publicadas.</p>');
        }
      });
    }
    function cargarFechasMes(year, month, cb){
      $.getJSON('api/articulos.php', { type:'calendar-month', year:year, month:month }, function(resp){
        cb(resp && resp.dates ? resp.dates : []);
      });
    }
    function cargarEventosDia(date){
      $.getJSON('api/articulos.php', { type:'calendar-day', date:date }, function(resp){
        var $ev = $('#eventos').empty();
        var $pop = $('#event-popover');
        if(resp && resp.data && resp.data.length){
          var listHtml = '<div class="pop-header"><span>Eventos</span><button class="pop-close" type="button" aria-label="Cerrar">×</button></div><ul>';
          function tw(s, n){ var arr = (s||'').trim().split(/\s+/); if(arr.length <= n) return s||''; return arr.slice(0, n).join(' ') + '…'; }
          resp.data.forEach(function(a){
            var href = 'templates/plantilla-articulo.php?slug=' + encodeURIComponent(a.slug);
            var html = '<a href="' + href + '"><div class="ev-card">'
              +   '<p class="ev-title">' + a.titulo + '</p>'
              +   '<p class="ev-desc">' + (a.descripcion_corta || '') + '</p>'
              + '</div></a>';
            $ev.append(html);
            listHtml += '<li><a href="' + href + '">' + tw(a.titulo, 8) + '</a></li>';
          });
          listHtml += '</ul>';
          $pop.html(listHtml).removeAttr('hidden');
          $pop.find('.pop-close').on('click', function(){ $pop.attr('hidden', true); });
        } else {
          $ev.html('<p class="c6 fz12">Sin eventos para esta fecha.</p>');
          $pop.attr('hidden', true).empty();
        }
      });
    }
    cargarNoticias();
    var hoy = new Date(); var y = hoy.getFullYear(); var m = hoy.getMonth() + 1;
    var fechasMes = {};
    $('#datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
      beforeShowDay: function(date){ var d = $.datepicker.formatDate('yy-mm-dd', date); var resaltado = !!fechasMes[d]; return [true, resaltado ? 'has-event' : '', resaltado ? 'Evento' : '']; },
      onChangeMonthYear: function(year, month){
        cargarFechasMes(year, month, function(dates){ fechasMes = {}; dates.forEach(function(d){ fechasMes[d] = true; }); $('#datepicker').datepicker('refresh'); });
      },
      onSelect: function(dateText){}
    });
    cargarFechasMes(y, m, function(dates){
      fechasMes = {}; dates.forEach(function(d){ fechasMes[d] = true; });
      $('#datepicker').datepicker('setDate', hoy);
      $('#datepicker').datepicker('refresh');
      var hoyStr = $.datepicker.formatDate('yy-mm-dd', hoy);
      cargarEventosDia(hoyStr);
    });
    $(document).on('click', function(e){ if(!$(e.target).closest('#event-popover, #datepicker').length){ $('#event-popover').attr('hidden', true); } });
  });
}

(function(){
  function makeChatbotResponsive(){
    var chatIframes = document.querySelectorAll('iframe[id*="chat"], iframe[class*="chat"], iframe[src*="tawk"], iframe[src*="intercom"]');
    chatIframes.forEach(function(iframe){
      if(window.innerWidth <= 768){ iframe.style.maxWidth = '320px'; iframe.style.maxHeight = '500px'; iframe.style.width = '100%'; }
      if(window.innerWidth <= 480){ iframe.style.maxWidth = '280px'; iframe.style.maxHeight = '450px'; iframe.style.right = '10px'; iframe.style.bottom = '10px'; }
    });
    var chatContainers = document.querySelectorAll('[id*="chatbot"], [class*="chatbot"], [id*="chat-widget"], [class*="chat-widget"]');
    chatContainers.forEach(function(container){
      if(window.innerWidth <= 768){
        container.style.maxWidth = '320px';
        container.style.maxHeight = '500px';
        var chatBody = container.querySelector('[class*="body"], [class*="messages"], [id*="messages"]');
        if(chatBody){ chatBody.style.maxHeight = '350px'; chatBody.style.overflowY = 'auto'; chatBody.style.overflowX = 'hidden'; chatBody.style.webkitOverflowScrolling = 'touch'; }
        var chatInput = container.querySelector('[class*="input"], input[type="text"], textarea');
        if(chatInput && chatInput.parentElement){
          chatInput.parentElement.style.position = 'sticky';
          chatInput.parentElement.style.bottom = '0';
          chatInput.parentElement.style.background = '#fff';
          chatInput.parentElement.style.zIndex = '1000';
          chatInput.parentElement.style.padding = '10px';
          chatInput.parentElement.style.boxShadow = '0 -2px 5px rgba(0,0,0,0.1)';
        }
      }
      if(window.innerWidth <= 480){
        container.style.maxWidth = '280px';
        container.style.maxHeight = '450px';
        var chatBody2 = container.querySelector('[class*="body"], [class*="messages"]');
        if(chatBody2){ chatBody2.style.maxHeight = '300px'; }
      }
    });
    var tawkFrames = document.querySelectorAll('iframe[src*="tawk"], iframe[src*="chat"]');
    tawkFrames.forEach(function(f){ f.style.pointerEvents = 'auto'; f.style.visibility = 'visible'; f.style.display = 'block'; });
  }
  if(document.readyState === 'loading'){ document.addEventListener('DOMContentLoaded', makeChatbotResponsive); } else { makeChatbotResponsive(); }
  window.addEventListener('resize', makeChatbotResponsive);
  var observer = new MutationObserver(function(){ makeChatbotResponsive(); });
  observer.observe(document.body, { childList: true, subtree: true });
  setTimeout(makeChatbotResponsive, 2000);
})();

function buildCandidates(s){
  var out = [];
  s = (s || '').trim();
  if(!s){ return ['images/demo/blog/1.jpg']; }
  var p = s.replace(/^\/+/, '');
  if(p.indexOf('Software-Institucional-CMS/') === 0){ p = p.substring('Software-Institucional-CMS/'.length); }
  else if(p.indexOf('policaribe/') === 0){ p = p.substring('policaribe/'.length); }
  p = p.replace(/^\/+/, '');
  out.push(window.location.origin + '/' + p);
  out.push(window.location.origin + '/Software-Institucional-CMS/' + p);
  out.push(window.location.origin + '/policaribe/' + p);
  if(/^https?:\/\//i.test(s)){ out.push(s); }
  var uniq = []; var seen = {};
  out.forEach(function(x){ if(!seen[x]){ seen[x] = 1; uniq.push(x); } });
  return uniq;
}

function fallbackImg(el, candidates){
  if(!candidates || candidates.length === 0){ el.src = 'images/demo/blog/1.jpg'; return; }
  var url = candidates.shift();
  var img = new Image();
  img.onload = function(){ el.src = url; };
  img.onerror = function(){ fallbackImg(el, candidates); };
  img.src = url;
}

document.addEventListener('DOMContentLoaded', function(){
  var imgs = document.querySelectorAll('img[data-src]');
  imgs.forEach(function(img){ var candidates = buildCandidates(img.getAttribute('data-src')); fallbackImg(img, candidates); });
});
