fetch(
  "https://barranquillas.unisimon.edu.co/siaaf/index.php/Unisimon_radio/team/obtener?__reportes=ReportesUsb"
)
  .then(response => response.text())
  .then(text => {
    try {
      const data = JSON.parse(text);
      data.msg.forEach((item, index) => {
        const elementos = document.getElementsByClassName('cargo');
        elementos[index].innerHTML = `${item.CARGO}`;
        const elemento = document.getElementsByClassName('nombre');
        elemento[index].innerHTML = `${item.NOMBRE}`; 
        const img = document.getElementsByClassName('card-image');
        img[index].style.backgroundImage = `url('${item.FOTO}')`;
 
      });
    } catch (e) {
    }
  });
