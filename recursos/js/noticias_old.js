fetch("https://barranquillas.unisimon.edu.co/siaaf/index.php/Unisimon_radio/noticias/noticias_obtener?__reportes=ReportesUsb")
  .then(response => response.json())
  .then(data => {
    const dataFinal = data.data.slice(0, 4);

    dataFinal.forEach(obj => {
        crearTarjeta(obj.NOMBREN, obj.DESCRIPCIONN, obj.IMAGENURLN,obj.CATEGORIA);
    });
  })
  .catch(error => {
    console.error("Error al obtener los datos:", error);
  });

  function crearTarjeta(nombreTexto, Descripcion, imagen, categoria) {
    const contenedor = document.querySelectorAll('.news-wrapper')[0];
  
    const color = categoria === "1"
      ? "#26A689"
      : categoria === "2"
        ? "#FF5500"
        : "#1877F2";
  
    categoria = categoria === "1"
      ? "Nuestra U"
      : categoria === "2"
        ? "Generales"
        : "Eventos";
  
    const span = document.createElement('span');
    span.textContent = categoria;
    span.className = 'categoria';
    
    span.style.cssText = `
      display: inline-block;
      padding: 0.3rem 0.8rem;
      border-radius: 12px;
      font-size: 0.70rem;
      font-weight: 500;
      color: white;
      height: auto;
      background: ${color};
      margin-top: 14px;
      margin-left:16px;
    `;
  
    const cardNews = document.createElement('div');
    cardNews.className = 'news-card';
    cardNews.style.cssText = `
      height: auto;
      display: flex;
      width: 20%;
      flex-direction: column;
      align-items: flex-start;
      
    `;
  
    const cardImage = document.createElement('div');
    cardImage.className = 'card-image-news';
    cardImage.style.backgroundImage = `url(${imagen})`;
    cardImage.style.height = '200px';
  
    const hNombre = document.createElement('h1');
    hNombre.className = 'news-title';
    hNombre.textContent = nombreTexto;
    hNombre.style.textAlign = 'left';
    hNombre.style.marginLeft = '16px';
    
    hNombre.style.fontSize = '0.90rem';
    hNombre.style.marginTop = '0.8rem';
  
    const pDescripcion = document.createElement('p');
    pDescripcion.className = 'news-description';
    pDescripcion.textContent = Descripcion;
    pDescripcion.style.textAlign = 'left';
    pDescripcion.style.marginTop = '0.6rem';
    pDescripcion.style.padding = '12px';
    pDescripcion.style.fontWeight = '400';


    

    if (window.innerWidth < 1400) {
        cardNews.style.cssText = `
        height: auto;
        display: flex;
        width: 40vw;
        flex-direction: column;
        align-items: flex-start;
        
      `;      }


      if (window.innerWidth < 500) {
        cardNews.style.cssText = `
        height: auto;
        display: flex;
        width: 80vw;
        flex-direction: column;
        align-items: flex-start;
        
      `;      }  
      

    cardNews.appendChild(cardImage);
    cardNews.appendChild(hNombre);
    cardNews.appendChild(span);
    cardNews.appendChild(pDescripcion);
    contenedor.appendChild(cardNews);
  }
  
  