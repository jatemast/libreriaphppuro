const form = document.getElementById("registerForm");
const box = document.getElementById("formBox");

form.addEventListener("submit", function (e) {
  const pass = document.getElementById("password").value.trim();
  const pass2 = document.getElementById("password_confirm").value.trim();

  if (pass === "" || pass2 === "") {
    e.preventDefault();
    animateError();
    return;
  }

  if (pass !== pass2) {
    e.preventDefault();
    alert("Las contraseñas no coinciden");
    animateError();
    return;
  }
});

function animateError() {
  box.classList.remove("error-shake");
  void box.offsetWidth; // Reinicia la animación
  box.classList.add("error-shake");
}
