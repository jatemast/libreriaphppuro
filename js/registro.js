const form = document.getElementById("loginForm");
const box = document.getElementById("formBox");

form.addEventListener("submit", function (e) {
  const email = document.getElementById("email").value.trim();
  const pass = document.getElementById("password").value.trim();
  if (email === "" || pass === "") {
    e.preventDefault();
    box.classList.remove("error-shake");
    void box.offsetWidth;
    box.classList.add("error-shake");
  }
});
