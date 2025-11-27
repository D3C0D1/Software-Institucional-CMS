document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.querySelector('.dropdown-button');
    const dropdownContent = document.querySelector('.dropdown-content');

    fetch(
        "https://barranquillas.unisimon.edu.co/siaaf/index.php/Unisimon_radio/reporte/temporadas?__reportes=ReportesUsb"
    )
        .then(response => response.json())
        .then(data => {
            const temporadasData = data.data;

            if (Array.isArray(temporadasData)) {
                temporadasData.forEach(temp => {
                           
                    btn.textContent = `Temporada ${temp.TEMPORADA}`;
                    const temporada = temp.TEMPORADA;  

                    btn.addEventListener("click", () => {
                        programas(temporada);  // Filtramos usando TEMPORADA
                        txt.textContent = `Temporada ${temp.TEMPORADA}`;  
                        
                        // Ocultar el dropdown después de seleccionar una temporada
                        dropdownContent.style.display = 'none';
                        dropdownContent.style.opacity = 0;
                        dropdownContent.style.transform = 'translateY(-10px)';
                        dropdownButton.classList.remove('open');
                    });

                    dropdownContent.appendChild(btn);
                });
            } else {
                console.error("temporadasData is not an array:", temporadasData);
            }
        })
        .catch(error => console.error("Error al obtener las temporadas:", error));

    dropdownButton.addEventListener("click", (event) => {
        event.stopPropagation();
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
            dropdownContent.style.opacity = 0;
            dropdownContent.style.transform = "translateY(-10px)";
            dropdownButton.classList.remove("open");
        } else {
            dropdownContent.style.display = "block";
            dropdownContent.style.opacity = 0;
            dropdownContent.style.transform = "translateY(-10px)";
            setTimeout(() => {
                dropdownContent.style.opacity = 1;
                dropdownContent.style.transform = "translateY(0)";
            }, 10);
            dropdownButton.classList.add("open");
        }
    });

    document.addEventListener("click", (event) => {
        if (event.target !== dropdownButton && !dropdownContent.contains(event.target)) {
            dropdownContent.style.display = "none";
            dropdownContent.style.opacity = 0;
            dropdownContent.style.transform = "translateY(-10px)";
            dropdownButton.classList.remove("open");
        }
    });

    window.addEventListener("beforeunload", () => {
        dropdownButton.classList.remove("open");
    });
});
