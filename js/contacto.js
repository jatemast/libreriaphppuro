document
  .getElementById("formContacto")
  .addEventListener("submit", function (e) {
    e.preventDefault(); // Evita el envío normal

    Swal.fire({
      title: "Enviando mensaje...",
      text: "Por favor espera un momento.",
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    fetch("enviar.php", {
      method: "POST",
      body: new FormData(this),
    })
      .then((res) => res.text())
      .then((data) => {
        if (data.includes("OK")) {
          Swal.fire({
            icon: "success",
            title: "¡Mensaje enviado!",
            text: "Tu mensaje se envió correctamente.",
          }).then(() => {
            window.location.href = "/libreria/index.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo enviar el mensaje.",
          });
          console.error("ERROR:", data);
        }
      });
  });
