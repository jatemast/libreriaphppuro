<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <title>Inicio Sesion</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="../src/registro.css" />
  </head>
  <body>
    <div class="login-container" id="formBox">
      <h2>Inicio de Sesion</h2>

      <form action="guardar_registro.php" method="POST" class="mt-4" id="loginForm">
        <!-- <div class="mb-3">
          <label class="form-label">Nombre:</label>
          <input type="text" class="form-control" name="nombre" required />
        </div> -->

        <div class="mb-3">
          <label class="form-label">Correo:</label>
          <input type="email" class="form-control" name="email" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Contraseña:</label>
          <input type="password" class="form-control" name="password" required />
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
      </form>
      
      <div class="register-link">
          <a href="registro.php">¿No tienes cuenta? Regístrate</a>
      </div>
      <div class="mt-3">
        <a href="../index.php" class="btn btn-secondary">Regresar al inicio</a>
      </div>
    </div>
    <script src="../js/registro.js"></script>
  </body>
</html>
