$(document).ready(function() {
/*Segmento codigo para links de otros micrositios*/
var url = new URL(window.location.href);
var c = url.searchParams.get("extnid");
if(c!=null || c != undefined){

  $('.tab-menu ul li').removeClass('active');
  $('.tab-menu ul li:nth-child(2)').addClass('active');
  $('.tab-menu ul li:nth-child(2)').trigger("click");


  $("div.tab-info div.talentoU").removeClass('activo');
  $("[data-id='"+c+"']").addClass('activo');
  setTimeout(function(){ $("div.tab-info div.talentoU.activo").trigger("click")}, 300);
}
/*Fin segmento codigo para links de otros micrositios*/
  var coun = 0;
      var scroll_critical = 1;
    $(window).scroll(function(){
        window_y = $(window).scrollTop(); // VALOR QUE SE HA MOVIDO DEL SCROLL
        if (coun < scroll_critical) {
          scroll_critical = $("#tope").offset().top; // VALOR DE TU DIV
          coun = scroll_critical;
        }

         if (window_y > scroll_critical) {
          $("#menu-es").css("position", "fixed");
        } else {
          if (window_y < coun) {
            $("#menu-es").css("position", "absolute");
          }

        }

  });
  $(".img-min").each(function(index, el) {
      $(this).css('margin-left', $(this).width() / 2 * -1);
    });
    $(".ctexto").each(function(index, el) {
      $(this).css('margin-top', $(this).height() / 2 * -1);
    });
    $(window).resize(function(){
      $(".ctexto").each(function(index, el) {
        $(this).css('margin-top', $(this).height() / 2 * -1);
      });
      $(".img-min").each(function(index, el) {
        $(this).css('margin-left', $(this).width() / 2 * -1);
      });
    });
    $( "#datepicker" ).datepicker({
      inline: true,
      dateFormat: "yy-mm-dd",
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      onSelect: function(textoFecha, objDatepicker){
        $.ajax({
          url:$("#varurl").attr("data-fecha"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"fecha="+textoFecha, //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#eventos").html(data)

          }
        });
       }
    });
    $(".datalink").click(function(event) {
      /* Act on the event */
      location.href=$(this).attr('data-id');
    });
    var pos = $("#Laspirante").position();
    $("#aspirantes").css('left', pos.left);
	var posb = $("#ElBlog").position();
    $("#elblog").css('left', posb.left);
    var posi = $("#Liie").position();
    $(".iieP").css('display', 'none');
    $(".iieP:first").css('display', 'block');
    $("#iie").css('left', posi.left - 300);
    $(".iie").mouseover(function(e) {
      /* Act on the event */
      $(".iie").removeClass('activo');
      $(this).addClass('activo');
      $(".iieP").css('display', 'none');
      $(".iiePt").css('display', 'none');
      $("#"+$(this).attr('data-id')).css('display', 'block')
    });
    $(".iiePt").css('display', 'none');
    $(".iiePt:first").css('display', 'block');
    $(".iiet").mouseover(function(e) {
        /* Act on the event */
        $(".iiet").removeClass('activo');
        $(this).addClass('activo');
        $(".iiePt").css('display', 'none');
        $("#"+$(this).attr('data-id')).css('display', 'block')
    });
    $("#cerrarS").click(function(event) {
      /* Act on the event */
      $("#servicios").animate({right: -250, opacity: 0}, 400)
    });
    $("#lservicios").click(function(e) {
      /* Act on the event */
       $("#servicios").animate({right: 0, opacity: 1}, 400)

      $("#iie").fadeOut(200)
      $("#aspirantes").fadeOut(200)
	  $("#elblog").fadeOut(200)
       $("#Liie").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
        $("#Laspirante").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
    });
    $("#Laspirante").mouseenter(function(e) {
      /* Act on the event */
      $("#iie").fadeOut(200)
       $("#Liie").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
      $(this).css({
        background: '#fff',
        color: '#4d4d4d',
        fontWeight: '400'
      });
      // $(this).find('a').css('padding', '14px 10px');
      $("#aspirantes").fadeIn(200)
      $(".img-min").each(function(index, el) {
        $(this).css('margin-left', $(this).width() / 2 * -1);
      });
    });
    $("#aspirantes").mouseleave(function(e) {
      /* Act on the event */
      $(this).fadeOut(200)
       $("#Laspirante").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
    });
	/* Gerardo Blog*/

	 $("#ElBlog").mouseenter(function(e) {
      /* Act on the event */
      $("#iie").fadeOut(200)
       $("#Liie").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
      $(this).css({
        background: '#fff',
        color: '#4d4d4d',
        fontWeight: '400'
      });
      // $(this).find('a').css('padding', '14px 10px');
      $("#elblog").fadeIn(200)
      $(".img-min").each(function(index, el) {
        $(this).css('margin-left', $(this).width() / 2 * -1);
      });
    });
    $("#elblog").mouseleave(function(e) {
      /* Act on the event */
      $(this).fadeOut(200)
       $("#Laspirante").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
    });

	/* Termina Gerardo */

    $("#Liie").mouseenter(function(e) {
      /* Act on the event */
      $("#aspirantes").fadeOut(200)
       $("#Laspirante").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
	   $("#elblog").fadeOut(200)
       $("#ElBlog").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
      $(this).css({
        background: '#fff',
        color: '#4d4d4d',
        fontWeight: '400'
      });
      // $(this).find('a').css('padding', '14px 10px');
      $("#iie").fadeIn(200)
      $(".img-min").each(function(index, el) {
        $(this).css('margin-left', $(this).width() / 2 * -1);
      });
    });
    $("#iie").mouseleave(function(e) {
      /* Act on the event */
      $(this).fadeOut(200)
       $("#Liie").css({
        background: 'transparent',
        color: '#fff',
        fontWeight: '300'
      });
    });
    $(".Vprograma").mouseenter(function(e) {
      /* Act on the event */
      $.obj = $(this).find('.dnone');
      $.obj.stop().fadeIn(300);
      $.obj.stop().animate({left: 0, opacity: 1}, 300)
      $.obj.find('.opa0').stop().animate({opacity: 1, marginLeft: 0}, 500)
    });
    $(".Vprograma").mouseleave(function(e) {
      /* Act on the event */
      $.obj = $(this).find('.dnone');
      $.obj.stop().fadeOut(300);
      $.obj.stop().animate({left: -100, opacity: 0}, 300)
      $.obj.find('.opa0').stop().animate({opacity: 0, marginLeft: -10}, 500)
    });
    $(".accordion-desc").fadeOut(0)
    $("#firstA").not($("#firstAc").next()).slideUp("fast")
    $("#firstAc").next().slideToggle(400)
    $(".accordion").click(function() {
      $(".accordion").find('img').attr('src', $("#varurl").attr("data-base")+'img/gr-13.png');
      $(".accordion").removeClass('activarA');
      $(".accordion").addClass('Anormal');
      $(this).addClass('activarA')
       $(this).find('img').attr('src', $("#varurl").attr("data-base")+'img/gr-12.png');
      $(".accordion-desc").not($(this).next()).slideUp("fast")
      $(this).next().slideToggle(400)
    });
    $(".accordionp-desc").fadeOut(0)
    $("#firstAp").not($("#firstAcp").next()).slideUp("fast")
    $("#firstAcp").next().slideToggle(400)
    $(".accordionp").click(function() {
      $(".accordionp").find('img').attr('src', 'img/gr-13.png');
      $(".accordionp").removeClass('activarAP');
      $(".accordionp").addClass('AnormalP');
      $(this).addClass('activarAP')
       $(this).find('img').attr('src', 'img/gr-12.png');
      $(".accordionp-desc").not($(this).next()).slideUp("fast")
      $(this).next().slideToggle(400)
    });
    $(".accordionc-desc").fadeOut(0)
    $("#firstAp").not($("#firstAcp").next()).slideUp("fast")
    $("#firstAcp").next().slideToggle(400)
    $(".accordionc").click(function() {
      $(".accordionc").find('img').attr('src', '../recursos/img/icon-25.png');
      // $(".accordionc").removeClass('activarAP');
      $(".accordionc").removeClass('activo');
      $(".accordionc").addClass('AnormalP');
      $(this).addClass('activo')
       $(this).find('img').attr('src', '../recursos/img/icon-24.png');
      $(".accordionc-desc").not($(this).next()).slideUp("fast")
      $(this).next().slideToggle(400)
    });
    $("#arriba").click(function(event) {
      /* Act on the event */
      $('body,html').stop(true,true).animate({
        //realizamos la animacion hacia el ancla
        scrollTop: 0
      },800);
    });
    $(".carpetas").click(function(event) {
      /* Act on the event */
      $(".carpetas").removeClass("activo");
      $(".carpetas").find(".mr5").attr('src', $("#varurl").attr('data-url')+'img/doc-21.png');
      $(".carpetas").find(".ml30").attr('src', '');
      $(this).find(".ml30").attr('src', $("#varurl").attr('data-url')+'img/p-20.png');
      $(this).find(".mr5").attr('src', $("#varurl").attr('data-url')+'img/doc-20.png');
      $(this).addClass("activo");
      $.ajax({
          url:$("#varurl").attr("data-id"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"cod="+$(this).attr("data-url"), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)

          }
        });
    });

    $("#buscadordoc").keypress(function(e) {
        $.ajax({
          url:$("#varurl").attr("data-cod"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"buscador="+$("#buscadordoc").val(), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)

          }
        });
    });

    $(".subCat").click(function(event) {
      /* Act on the event */
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $(".postgrados"+cod).html(data)
            $(".ctexto").each(function(index, el) {
              $(this).css('margin-top', $(this).height() / 2 * -1);
            });
          }
        });
    });

    $(".talentoH").click(function(event) {
      /* Act on the event */
      $(".talentoH").removeClass('activo');
      $(this).addClass('activo');
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
            var strAncla=$("#result");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });

    $(".catnoticias").click(function(event) {
      /* Act on the event */
      $("#busqueda").val("");
      $(".catnoticias").css('color', '#666666');
      $(this).css('color', '#05a64a');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
          }
        });
    });

    $("#busqueda").keypress(function(e) {
        if (e.keyCode == 13) {
          $.ajax({
            url:$("#varurl").attr("data-bus"), //Url a donde la enviaremos
            type:'POST', //Metodo que usaremos
            data:"buscador="+$("#busqueda").val(), //Le pasamos el objeto que creamos con los archivos
            beforeSend:function(){},
            success: function(data){
              $("#result").html(data)

            }
          });
        }
    });

    $(".lcredito").click(function(event) {
      /* Act on the event */
      $(".lcredito").css('color', '#666666');
      //$(".lcredito").find('img').attr('src', '');
      $(".lcredito").find('img').css('display', 'none');
      $(this).find('img').css('display', '');
      //$(this).find('img').attr('src', $("#varurl").attr('data-base')+'img/ir-30.png');
      $(this).css('color', '#05ac4a');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
            var strAncla=$("#result");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });

    $(".ladmisiones").click(function(event) {
      /* Act on the event */
      $(".ladmisiones").css({
        color: '#808080',
        fontWeight: '400'
      });
      $(".ladmisiones").find('img').attr('src', '');
      $(this).find('img').attr('src', $("#varurl").attr('data-base')+'img/ir-30.png');
      $(this).css({
        color: '#333333',
        fontWeight: '500'
      });
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
          }
        });
    });

    $(".univer").click(function(event) {
      /* Act on the event */
      $(".univer").removeClass('activo');
      $(this).addClass('activo');
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result2").html(data)
            var strAncla=$("#result2");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });

    $(".univer2").click(function(event) {
      /* Act on the event */
      $(".univer2").removeClass('activo');
      $(this).addClass('activo');
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result3").html(data)
            var strAncla=$("#result3");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });

    $(".talentoU").click(function(event) {
      /* Act on the event */
      $(".talentoU").removeClass('activo');
      $(this).addClass('activo');
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-id"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
            var strAncla=$("#result");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });

    $(".teatro").click(function(event) {
      /* Act on the event */
      $(".teatro").removeClass('activo');
      $(this).addClass('activo');
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
            var strAncla=$("#result");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });

    $(".acorinfo").click(function(event) {
      /* Act on the event */
      var cod = $(this).attr('data-cod');
      $.ajax({
          url:$("#varurl").attr("data-url"), //Url a donde la enviaremos
          type:'POST', //Metodo que usaremos
          data:"id="+$(this).attr('data-id'), //Le pasamos el objeto que creamos con los archivos
          beforeSend:function(){},
          success: function(data){
            $("#result").html(data)
            var strAncla=$("#result");
            //utilizamos body y html, ya que dependiendo del navegador uno u otro no funciona
            $('body,html').stop(true,true).animate({
              //realizamos la animacion hacia el ancla
              scrollTop: $(strAncla).offset().top
            },800);
          }
        });
    });
    var ii = 0;
    $(".pdirec").click(function(event) {
      /* Act on the event */
      if (ii == 0) {
        $(this).animate({height: 50}, 400);
        ii = 1;
      } else {
        ii = 0;
        $(this).animate({height: 20}, 400);
      }
    });
    $(".accordionb-desc").fadeOut(0)
    $(".tab-info .accordionb:first").next().slideToggle(400)
    $(".tab-info .accordionb:first").find('img').attr('src', $("#varurl").attr("data-url")+'img/aba-29.png');
    $(".tab-info .accordionb:first").addClass('be7');
    $(".tab-info .accordionb:first").addClass('c29a');
    $(".accordionb").click(function() {
      $(".accordionb").find('img').attr('src', $("#varurl").attr("data-url")+'img/aba-28.png');
      $(".accordionb").removeClass('be7');
      $(".accordionb").removeClass('c29a');
      $(this).addClass('be7');
      $(this).addClass('c29a');
      $(this).find('img').attr('src', $("#varurl").attr("data-url")+'img/aba-29.png');
      $(".accordionb-desc").not($(this).next()).slideUp("fast")
      $(this).next().slideToggle(400)
    });
    $("#buscarPre").keypress(function(e) {
      if(e.which == 13) {
      $.ajax({
        url:$("#varbus").attr("data-url"), //Url a donde la enviaremos
        type:'POST', //Metodo que usaremos
        data:"buscador="+$("#buscarPre").val(), //Le pasamos el objeto que creamos con los archivos
        beforeSend:function(){},
        success: function(data){
          $("#resultP").html(data)
          $(".img-min").each(function(index, el) {
            $(this).css('margin-left', $(this).width() / 2 * -1);
          });
        }
      });
    }
    });
});
function buscadorc() {
  $.ajax({
    url:$("#varurl").attr("data-cod"), //Url a donde la enviaremos
    type:'POST', //Metodo que usaremos
    data:"buscador="+$("#buscadordoc").val(), //Le pasamos el objeto que creamos con los archivos
    beforeSend:function(){},
    success: function(data){
      $("#result").html(data)

    }
  });
}
function buscarPregrado() {
  $.ajax({
    url:$("#varbus").attr("data-url"), //Url a donde la enviaremos
    type:'POST', //Metodo que usaremos
    data:"buscador="+$("#buscarPre").val(), //Le pasamos el objeto que creamos con los archivos
    beforeSend:function(){},
    success: function(data){
      $("#resultP").html(data)
      $(".img-min").each(function(index, el) {
        $(this).css('margin-left', $(this).width() / 2 * -1);
      });
    }
  });
}
function newsletter() {
  $.ajax({
     url:$("#varnews").attr("data-news"), //Url a donde la enviaremos
     type:'POST', //Metodo que usaremos
     data:"correo="+$("#correo_n").val(), //Le pasamos el objeto que creamos con los archivos
     beforeSend:function(){
        // $("#btn").val("ENVIANDO...");
     },
     success: function(data){
       console.log(data);
       if($.trim(data)=="1"){
         // location.href="";
         $("#correo_n").val("")
         alert("Gracias por suscribirse")
       } else {
          alert(data);
       }
     }
  });
}
function correo() {
  if ($("#frm-contacto").valid()){
    $('#mensaje').val($('#mensaje').val().replace(new RegExp('\\n','g'),' ').replace(new RegExp('\\r','g'),' '))
    $.ajax({
       url:$("#varurl").attr("data-email"), //Url a donde la enviaremos
       type:'POST', //Metodo que usaremos
       data:$("#frm-contacto").serialize(), //Le pasamos el objeto que creamos con los archivos
       beforeSend:function(){
          // $("#btn").val("ENVIANDO...");
       },
       success: function(data){
         if(data=="1"){
           // location.href="";
          document.getElementById("frm-contacto").reset();
           alert("Gracias por escribirnos. Pronto nos pondremos en contacto con usted.")
         } else {
            alert(data);
         }
       }
    });
  }
}
function InitTabs(selector, initIndex, clase, callback)
  {
    $(selector).each(function(index)
    {
      $p = $(this);
      if(callback) $p.data("bn-tabs-callback",  callback);
      $p.addClass(clase);
      $p.find(".tab-menu").on("click", "li", function(e)
      {
        $o = $(e.currentTarget);
        $p = $o.parent().parent().parent();
        setTabIndex($p, $p.find(".tab-menu li").index($o));
      });

      if(initIndex != undefined) setTabIndex($p, initIndex);
    });
  }

  function setTabIndex($p, index)
  {
    $tabs = $p.find(".tab-menu");
    $cont = $p.find(".tab-container");
    $tabs.find("ul li").removeClass("active");
    $o = $tabs.find("ul li").eq(index);
    $o.addClass("active");
    $cont.find(".active").stop().fadeOut(0).removeClass("active");
    $cont.find(".tab-info").eq(index).fadeIn(1000).addClass("active");
    $ind = $tabs.find("#indicador");
    if($ind.css("display") == "none")
    {
      $ind.show(0);
      $ind.width($o.outerWidth(true));
    }
    $ind.animate({width:($o.outerWidth(true) - 10) + "px", left:$o.position().left + 5}, 300);
    if($p.data("bn-tabs-callback")) $p.data("bn-tabs-callback")($p, index);
    $(".img-min").each(function(index, el) {
        $(this).css('margin-left', $(this).width() / 2 * -1);
      });
    $(".ctexto").each(function(index, el) {
        $(this).css('margin-top', $(this).height() / 2 * -1);
      });
  }

  function InitTabsA(a, t, e) {
    $(a).each(function(a) {
        $p = $(this), e && $p.data("bn-tabs-callback", e), $p.addClass("bn-tabsA"), $p.find(".tab-menu").on("click", "li", function(a) {
            $o = $(a.currentTarget), $p = $o.parent().parent().parent(), setTabIndex($p, $p.find(".tab-menu li").index($o))
        }), void 0 != t && setTabIndexA($p, t)
    })
}

function setTabIndexA(a, t) {
    $tabs = a.find(".tab-menu"), $cont = a.find(".tab-container"), $tabs.find("ul li").removeClass("active"), $o = $tabs.find("ul li").eq(t), $o.addClass("active"), $cont.find(".active").stop().fadeOut(0).removeClass("active"), $cont.find(".tab-info").eq(t).fadeIn(1e3).addClass("active"), $ind = $tabs.find("#indicador"), "none" == $ind.css("display") && ($ind.show(0), $ind.width($o.outerWidth(!0))), $ind.animate({
        width: $o.outerWidth(!0) - 10 + "px",
        left: $o.position().left + 5
    }, 300), a.data("bn-tabs-callback") && a.data("bn-tabs-callback")(a, t)
}
