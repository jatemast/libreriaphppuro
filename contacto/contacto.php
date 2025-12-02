<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto</title>

  <link rel="stylesheet" href="../src/contacto.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
   <div class="container">
    <a href="../index.php" class="btn-back"> Regresar al inicio</a>
        <form id="formContacto" action="enviar.php" method="POST">
            <h2>Contáctanos</h2>

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" required>

            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

            <button type="submit">Enviar mensaje</button>
        </form>
    </div>

    <script src="../js/contacto.js"></script>

</body>
</html>



