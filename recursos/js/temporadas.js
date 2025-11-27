
function programas(ID) {
    const apiUrl = "https://barranquillas.unisimon.edu.co/siaaf/index.php/Unisimon_radio/reporte/programas?__reportes=ReportesUsb";

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data || !Array.isArray(data.data)) {
                return;
            }

            const programasData = data.data;

            // Referencias
            const dropdownContent = document.querySelector('.dropdown-content');
            const txt = document.getElementById('Text-dropdown');
            const mostrar = document.querySelector(".mostrar");

            if (!dropdownContent || !txt || !mostrar) return;

            // Vaciar dropdown y mostrar
            dropdownContent.innerHTML = "";
            mostrar.innerHTML = "";

            const idsUnicos = [...new Set(programasData.map(item => item.TEMPPROG_ID))];

            if (!ID) {
                ID = idsUnicos[idsUnicos.length - 1];
            }

            idsUnicos.forEach(idTemp => {
                const link = document.createElement('a');
                link.textContent = `Temporada ${idTemp}`;
                link.href = "#";
                link.dataset.id = idTemp;
                link.style.display = "block";
                link.style.width = "100%";
                link.style.height = "50px";
              link.style.padding = "5px 10px";
                 link.classList.add('menu-drop');
                link.style.textDecoration = "none";
                link.style.color = "inherit";
                link.style.cursor = "pointer";
                link.style.textAlign = "left";
                link.style.paddingTop="15px";  
                link.style.paddingBottom="20px";
                // Marcar activo
                if (Number(idTemp) === Number(ID)) {
                    link.style.fontWeight = "bold";
                } else {
                    link.style.fontWeight = "normal";
                }

                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    txt.textContent = `Temporada ${idTemp}`;
                    programas(idTemp);

                    dropdownContent.classList.remove('show');
                });

                dropdownContent.appendChild(link);
            });

            txt.textContent = `Temporada ${ID}`;

            const filteredItems = programasData.filter(item => Number(item.TEMPPROG_ID) === Number(ID));

            if (filteredItems.length === 0) {
                mostrar.textContent = "No hay programas para esta temporada.";
                return;
            }

            filteredItems.forEach(item => {
                const btnp = document.createElement("button");
                btnp.style.backgroundSize = "cover";
                btnp.style.backgroundPosition = "center";
                btnp.style.width = "12vw";
                btnp.style.height= "10vw";
                btnp.style.margin = "5px";

                if (item.IMAGEN) {
                    btnp.style.backgroundImage = `url('${item.IMAGEN}')`;
                } else {
                    btnp.textContent = "Sin imagen";
                }

                btnp.classList.add("prg");

                btnp.addEventListener('click', () => {
                    saludar(item.WIDGET);
                });

                mostrar.appendChild(btnp);
            });
        })
        .catch(error => {
            const mostrar = document.querySelector(".mostrar");
            if (mostrar) {
                mostrar.innerHTML = "Error al cargar los programas.";
            }
        });
}

document.getElementById('favorite-animal-dropdown').addEventListener('click', () => {
    const dropdownContent = document.querySelector('.dropdown-content');
    if (!dropdownContent) return;

    dropdownContent.classList.toggle('show');
});

programas();

function saludar(Widget){
const elemento = document.querySelector('.framew');
if (elemento) {
  elemento.src = Widget;   
}

}

