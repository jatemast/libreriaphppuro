<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Importamos la libreria de funciones de la API
require_once __DIR__ . "/logica/funciones_api.php";

// Obtenemos los libros de la API
$libros_destacados = obtenerLibrosDeAPI();

// Si la obtención de libros falla, se establecerá como un array vacío para evitar errores.
if ($libros_destacados === null) {
    $libros_destacados = [];
}
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

        <?php if (isset($_SESSION['user_name'])): ?>
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
            <?php echo $_SESSION['user_name']; ?>
          </a>

          <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <!-- Por ahora, redirigimos a todos los usuarios a su panel personal por defecto.
                 Si la API proporciona roles, esta lógica necesitará ser revisada. -->
            <a class="dropdown-item" href="usuarios/index.php">
                Mi perfil
            </a>
        </li>

    <li><a class="dropdown-item" href="logica/logout.php">Cerrar sesión</a></li>
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
    if (!empty($libros_destacados)):
        $librosPorPagina = 6; // Cantidad de libros a mostrar por página
        $totalLibros = count($libros_destacados);
        $totalPaginas = ceil($totalLibros / $librosPorPagina);

        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $paginaActual = max(1, min($paginaActual, $totalPaginas)); // Asegura que la página sea válida

        $offset = ($paginaActual - 1) * $librosPorPagina;
        $libros_a_mostrar = array_slice($libros_destacados, $offset, $librosPorPagina);

        foreach ($libros_a_mostrar as $libro):
    ?>
    <div class="col-md-4 mb-4">
        <div class="card libro-card">
            <a href="libros/ver_libros.php?id=<?= $libro['id'] ?>" class="sin-estilo">

                <!-- Imagen del libro desde la URL de la API -->
                <?php if (!empty($libro['image_url'])): ?>
                    <img
                        src="<?= htmlspecialchars($libro['image_url']) ?>"
                        class="card-img-top portada-libro"
                        alt="<?= htmlspecialchars($libro['name']) ?>"
                    >
                <?php else: ?>
                    <img
                        src="img/libros/reload.jpg"
                        class="card-img-top libro-imagen"
                        alt="Imagen no disponible"
                    >
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title texto-blanco">
                        <?= htmlspecialchars($libro['name']) ?>
                    </h5>
                    <!-- No hay autor directo en la API, se muestra el precio -->
                    <p class="card-text texto-blanco">
                        Precio: $<?= htmlspecialchars($libro['price']) ?>
                    </p>
                </div>

            </a>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="card-footer text-center">
                    <a href="libros/editar_libro.php?id=<?= $libro['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
        endforeach;
    else:
    ?>
    <p>No se pudieron cargar los libros destacados en este momento o no hay libros disponibles.</p>
    <?php endif; ?>

</div>

    <!-- Paginación -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($paginaActual > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $paginaActual - 1 ?>">Anterior</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $paginaActual + 1 ?>">Siguiente</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/index.js"></script>

  </body>
</html>
