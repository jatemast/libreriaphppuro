// Animación cuando se actualiza el carrito
document.addEventListener("DOMContentLoaded", () => {
  const table = document.querySelector("table");

  if (table) {
    table.style.opacity = "0";
    setTimeout(() => {
      table.style.transition = "0.6s";
      table.style.opacity = "1";
    }, 200);
  }
});

// Confirmación personalizada
function confirmarBorrado() {
  return confirm("¿Eliminar este libro del carrito?");
}
