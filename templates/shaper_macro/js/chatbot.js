// Chatbot Policaribe: botón flotante y panel con lógicas básicas
(function(){
  const waNumber = '573116149733';
  const waUrl = `https://wa.me/${waNumber}`;

  const programs = [
    { title: 'Auxiliar Administrativo', url: 'programas/auxiliar-administrativo.html' },
    { title: 'Preparación y Entrenamiento Físico', url: 'programas/preparacion-yentrenamiento-fisico.html' },
    { title: 'Seguridad Ocupacional y Laboral', url: 'programas/seguridad-ocupacionalylaboral.html' },
    { title: 'Asistente de Marketing y Comunicaciones', url: 'programas/asistente-marketing-y-comunicaciones.html' },
    { title: 'Auxiliar en Enfermería', url: 'programas/auxiliar-en-enfermeria.html' },
    { title: 'Auxiliar Contable y Financiero', url: 'programas/auxiliar-contable-y-financiero.html' },
    { title: 'Animación Gráfica y de Multimedia', url: 'programas/animacion-graficayde-multimedia.html' },
    { title: 'Auxiliar en Educación para la Primera Infancia', url: 'programas/auxiliar-en-educacion-para-la-primera-infancia.html' }
  ];

  // Inyecta el widget si no existe (para que funcione en todas las páginas)
  function ensureWidget(){
    var existing = document.getElementById('chatbot-widget');
    if(!existing){
      var wrap = document.createElement('div');
      wrap.id = 'chatbot-widget';
      wrap.setAttribute('aria-live','polite');
      document.body.appendChild(wrap);
      existing = wrap;
    }
    // Insert inline bubble + toggle if missing
    if(!existing.querySelector('.chatbot-inline')){
      var inline = document.createElement('div');
      inline.className = 'chatbot-inline';
      inline.innerHTML = `
        <button class="chatbot-bubble" id="chatbot-cta" title="Contacta a un asesor">Contacta a un asesor <span class="wave-emoji">👋</span></button>
        <button id="chatbot-toggle" aria-expanded="false" aria-controls="chatbot-panel" title="Chat Policaribe">
          <img src="images/ico.ico" alt="Chatbot" />
        </button>`;
      existing.appendChild(inline);
    }
    if(!existing.querySelector('#chatbot-panel')){
      var panel = document.createElement('div');
      panel.id = 'chatbot-panel';
      panel.setAttribute('role','dialog');
      panel.setAttribute('aria-label','Chat de Policaribe');
      panel.hidden = true;
      panel.innerHTML = `
        <div class="chatbot-header">
          <strong>Asistente Policaribe</strong>
          <button class="chatbot-close" aria-label="Cerrar">×</button>
        </div>
        <div class="chatbot-body">
          <div class="chatbot-messages">
            <div class="msg bot">Hola 👋 Soy tu asistente virtual. Escribe tu pregunta abajo o usa estos enlaces rápidos:</div>
            <div class="quick-links">
              <a href="#" data-msg="Programas disponibles">📚 Programas</a>
              <a href="#" data-msg="Sobre nosotros">ℹ️ Sobre nosotros</a>
              <a href="#" data-msg="Inscripciones">📝 Inscripciones</a>
              <a href="#" data-msg="PQRS">📋 PQRS</a>
              <a href="${waUrl}" target="_blank" rel="noopener">💬 Asesor WhatsApp</a>
            </div>
          </div>
          <form class="chatbot-input" autocomplete="off">
            <input type="text" name="q" placeholder="Escribe tu pregunta..." aria-label="Mensaje" />
            <button type="submit" aria-label="Enviar">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
              </svg>
            </button>
          </form>
        </div>`;
      existing.appendChild(panel);
    }
  }

  ensureWidget();

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

  function openPanel(){ panel.hidden = false; toggle.setAttribute('aria-expanded','true'); }
  function closePanel(){ panel.hidden = true; toggle.setAttribute('aria-expanded','false'); }
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
      linkMsg('Sobre nosotros', 'quienes-somos.html');
      return;
    }
    if(s.includes('pqrs') || s.includes('peticiones') || s.includes('quejas') || s.includes('reclamos') || s.includes('sugerencias')){
      addMsg('Para PQRS puedes escribirnos a contacto@policaribe.edu.co o usar este enlace:', 'bot');
      linkMsg('Abrir WhatsApp asesor', waUrl);
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
      linkMsg('Calendario Académico Virtual (PDF)', 'documentos/calendario-academico-virtual.pdf');
      linkMsg('Calendario Académico a Distancia (PDF)', 'documentos/calendario-academico-a-distancia.pdf');
      return;
    }
    if(s.includes('estatuto') || s.includes('reglamento')){
      addMsg('Aquí puedes ver nuestro Estatuto General:', 'bot');
      linkMsg('Estatuto General (PDF)', 'documentos/estatuto-general.pdf');
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