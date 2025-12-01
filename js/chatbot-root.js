// Chatbot Policaribe para index.html raíz
(function(){
  const waNumber = '573116149733';
  const waUrl = `https://wa.me/${waNumber}`;

  const programs = [
    { title: 'Auxiliar Administrativo', url: 'policaribe/programas/auxiliar-administrativo.html' },
    { title: 'Preparación y Entrenamiento Físico', url: 'policaribe/programas/preparacion-yentrenamiento-fisico.html' },
    { title: 'Seguridad Ocupacional y Laboral', url: 'policaribe/programas/seguridad-ocupacionalylaboral.html' },
    { title: 'Asistente de Marketing y Comunicaciones', url: 'policaribe/programas/asistente-marketing-y-comunicaciones.html' },
    { title: 'Auxiliar en Enfermería', url: 'policaribe/programas/auxiliar-en-enfermeria.html' },
    { title: 'Auxiliar Contable y Financiero', url: 'policaribe/programas/auxiliar-contable-y-financiero.html' },
    { title: 'Animación Gráfica y de Multimedia', url: 'policaribe/programas/animacion-graficayde-multimedia.html' },
    { title: 'Auxiliar en Educación para la Primera Infancia', url: 'policaribe/programas/auxiliar-en-educacion-para-la-primera-infancia.html' }
  ];

  function el(id){ return document.getElementById(id); }
  const widget = el('chatbot-widget');
  const toggle = el('chatbot-toggle');
  const cta = document.getElementById('chatbot-cta');
  const panel = el('chatbot-panel');
  if(!widget || !toggle || !panel) return;

  const closeBtn = panel.querySelector('.chatbot-close');
  const form = panel.querySelector('.chatbot-input');
  const input = panel.querySelector('.chatbot-input input');
  const messages = panel.querySelector('.chatbot-messages');
  const quickLinks = panel.querySelectorAll('.quick-links a');

  function openPanel(){ 
    panel.hidden = false; 
    panel.style.display = 'flex';
    toggle.setAttribute('aria-expanded','true'); 
  }
  function closePanel(){ 
    panel.hidden = true; 
    panel.style.display = 'none';
    toggle.setAttribute('aria-expanded','false'); 
  }
  function addMsg(text, who){
    const div = document.createElement('div');
    div.className = `msg ${who}`;
    div.textContent = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }
  function linkMsg(text, href){
    const div = document.createElement('div');
    div.className = 'msg bot';
    const a = document.createElement('a');
    a.href = href; a.textContent = text; a.style.fontWeight = '700'; a.target = '_blank'; a.rel = 'noopener';
    div.appendChild(a); messages.appendChild(div); messages.scrollTop = messages.scrollHeight;
  }

  // Intenciones y respuestas afinadas
  function respond(q){
    const s = q.toLowerCase();
    if(s.includes('programa') || s.includes('carrera') || s.includes('curso')){
      addMsg('Estos son nuestros programas técnicos laborales:', 'bot');
      programs.forEach(p=> linkMsg(p.title, p.url));
      addMsg('¿Te interesa alguno? Puedo darte requisitos o inscribirte.', 'bot');
      return;
    }
    if(s.includes('sobre nosotros') || s.includes('quienes somos') || s.includes('nosotros')){
      addMsg('Conoce nuestra historia, misión y valores:', 'bot');
      linkMsg('Sobre nosotros', 'policaribe/quienes-somos.html');
      return;
    }
    if(s.includes('pqrs') || s.includes('peticiones') || s.includes('quejas') || s.includes('reclamos') || s.includes('sugerencias')){
      addMsg('Para PQRS puedes escribirnos a contacto@policaribe.edu.co o usar este enlace:', 'bot');
      linkMsg('Formulario PQRS', 'pqrs.html');
      return;
    }
    if(s.includes('inscripcion') || s.includes('inscripciones') || s.includes('preinscripcion')){
      addMsg('Puedes realizar tu preinscripción en línea aquí:', 'bot');
      linkMsg('Preinscripción en línea', 'https://site2.q10.com/preinscripcion?aplentId=cd2173fa-287d-40c7-84a7-ce6098ccf063');
      addMsg('Requisitos generales: documento de identidad, certificado académico y foto. Si necesitas ayuda, te conecto con un asesor.', 'bot');
      return;
    }
    if(s.includes('calendario') || s.includes('fechas')){
      addMsg('Consulta el calendario académico:', 'bot');
      linkMsg('Calendario Académico Virtual (PDF)', 'policaribe/documentos/calendario-academico-virtual.pdf');
      linkMsg('Calendario Académico a Distancia (PDF)', 'policaribe/documentos/calendario-academico-a-distancia.pdf');
      return;
    }
    if(s.includes('estatuto') || s.includes('reglamento')){
      addMsg('Aquí puedes ver nuestro Estatuto General:', 'bot');
      linkMsg('Estatuto General (PDF)', 'policaribe/documentos/estatuto-general.pdf');
      return;
    }
    if(s.includes('whatsapp') || s.includes('asesor') || s.includes('contacto')){
      linkMsg('Hablar con asesor por WhatsApp', waUrl);
      return;
    }
    // fallback
    addMsg('Puedo ayudarte con programas, sobre nosotros, PQRS, calendario, estatuto o inscripciones. Si lo prefieres, te conecto con un asesor por WhatsApp.', 'bot');
  }

  // Interactions
  function openFromCTA(e){ if(e) e.preventDefault(); openPanel(); }
  toggle.addEventListener('click', (e)=>{ e.preventDefault(); panel.hidden ? openPanel() : closePanel(); });
  toggle.addEventListener('mouseenter', openPanel);
  if(cta) cta.addEventListener('click', openFromCTA);
  closeBtn.addEventListener('click', closePanel);
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closePanel(); });

  // Inicializar el panel como cerrado
  closePanel();

  // Quick links
  quickLinks.forEach(link=>{
    link.addEventListener('click', (e)=>{
      const msg = link.dataset.msg;
      if(!msg){ return; } // es el link de WhatsApp externo
      e.preventDefault();
      addMsg(msg, 'user');
      respond(msg);
    });
  });

  // Form submit
  form.addEventListener('submit', (e)=>{
    e.preventDefault();
    const q = (input.value || '').trim();
    if(!q) return;
    addMsg(q, 'user'); input.value = '';
    respond(q);
  });
})();
