const form = document.getElementById("form2fa");
const box = document.getElementById("box");
const codigo = document.getElementById("codigo");

form.addEventListener("submit", (e) => {
  if (codigo.value.trim() === "") {
    e.preventDefault();

    // Reiniciar animación
    box.classList.remove("shake");
    void box.offsetWidth;
    box.classList.add("shake");
  }
});
