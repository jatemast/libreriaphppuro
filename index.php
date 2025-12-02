<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
//Importamos la libreria de la base de datos
require_once __DIR__ . "/logica/conexion.php";


//Codigo que nos ayuda a que las imagenes se muestren y se cambien aleatoriamaente
$result = $conexion->query("
    SELECT id_libro,titulo, descripcion, imagen 
    FROM libros 
    ORDER BY RAND() DESC 
    LIMIT 6
");
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <title>Librería — Inicio</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      
    />
    <link rel="stylesheet" href="./src/index.css" />
  </head>

  <body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">

    <a class="navbar-brand" href="index.php">Librería</a>

    <!-- BOTÓN HAMBURGUESA -->
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarContent"
      aria-controls="navbarContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENÚ QUE SE COLAPSA EN CELULAR -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">

        <?php if (isset($_SESSION['nombre'])): ?>
        <!-- Usuario logueado -->
        <li class="nav-item dropdown d-flex align-items-center">
          <img
            src="img/user.png"
            alt="usuario"
            class="rounded-circle me-2"
            width="35"
            height="35"
          />

          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="usuarioMenu"
            role="button"
            data-bs-toggle="dropdown"
          >
            <?php echo $_SESSION['nombre']; ?>
          </a>

          <ul class="dropdown-menu dropdown-menu-end">

    <?php 
    // Si el usuario es administrador → va a la administración
    if ($_SESSION['rol'] === 1): ?>
    
        <li>
            <a class="dropdown-item" href="administrador/index.php">
                Mi perfil (Administrador)
            </a>
        </li>

    <?php else: ?>
    
        <!-- Si es usuario normal → va a su panel personal -->
        <li>
            <a class="dropdown-item" href="usuarios/index.php">
                Mi perfil (Cliente)
            </a>
        </li>

    <?php endif; ?>

    <li><a class="dropdown-item" href="../logica/logout.php">Cerrar sesión</a></li>
</ul>
        </li>

        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="logica/login.php">Inicio de sesión</a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="libros/ver_libros.php">Comprar</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="libros/ver_libros.php">Libros</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto/contacto.php">Contactanos</a></li>

      </ul>
    </div>
  </div>
</nav>
    <header class="p-5 text-center bg-primary text-white">
      <h1>Bienvenido</h1>
      <p>Encuentra tus libros favoritos y haz tu compra en línea</p>
    </header>
   <div class="container mt-4">
    <h2>Catálogo destacado</h2>

    <div class="row mt-3">

    <?php 
    $result = $conexion->query("
        SELECT libros.id_libro, libros.titulo, libros.imagen,
               autores.nombre AS autor_nombre, autores.apellido AS autor_apellido
        FROM libros
        INNER JOIN autores ON libros.id_autor = autores.id_autor
        ORDER BY RAND()
        LIMIT 6
    ");

    while ($libro = $result->fetch_assoc()): 
    ?>
    <div class="col-md-4 mb-4">
        <div class="card libro-card">
            <a href="libros/ver_libros.php?id=<?= $libro['id_libro'] ?>" class="sin-estilo">

                <!-- Imagen tipo libro vertical -->
                <?php if (!empty($libro['imagen'])): ?>
                    <img 
                        src="data:image/jpeg;base64,<?= base64_encode($libro['imagen']) ?>"
                        class="card-img-top portada-libro"
                    >
                <?php else: ?>
                    <img 
                        src="img/libros/reload.jpg"
                        class="card-img-top libro-imagen"
                    >
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title texto-blanco">
                        <?= htmlspecialchars($libro['titulo']) ?>
                    </h5>

                    <!-- AUTOR EN LUGAR DE DESCRIPCIÓN -->
                    <p class="card-text texto-blanco">
                        <?= $libro['autor_nombre'] . " " . $libro['autor_apellido'] ?>
                    </p>
                </div>

            </a>
        </div>
    </div>
    <?php endwhile; ?>

</div>


    
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/index.js"></script>

  </body>
</html>
