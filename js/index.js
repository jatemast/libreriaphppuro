// Animación al cargar las cards
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".card");

  cards.forEach((card, i) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    setTimeout(() => {
      card.style.transition = "0.6s";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, i * 150);
  });
});

// Pequeño efecto vibración suave al pasar el mouse
document.querySelectorAll(".card").forEach((card) => {
  card.addEventListener("mouseenter", () => {
    card.style.transition = "0.1s";
    card.style.transform = "scale(1.03)";
  });

  card.addEventListener("mouseleave", () => {
    card.style.transition = "0.3s";
    card.style.transform = "scale(1)";
  });
});
