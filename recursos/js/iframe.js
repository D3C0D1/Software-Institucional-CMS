const alto = window.innerHeight;
const ancho = window.innerWidth;
var elemento = document.getElementById("soundcloud");
const width = 0;
const height = 0;

if (ancho > 800) {
    elemento.style.width = "300%";
    elemento.style.height = "550";
    
} else {
    elemento.style.width = "100%";
    elemento.style.height = "250";
}