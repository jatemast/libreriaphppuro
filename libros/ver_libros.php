<?php
session_start();

require __DIR__ . '/../logica/conexion.php';


// Obtener todos los libros
$result = $conexion->query("
    SELECT id_libro, titulo, descripcion, imagen, precio
    FROM libros
    ORDER BY id_libro DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Libros</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">

   <!-- BOTONES SUPERIORES -->
    <div class="d-flex justify-content-between mb-3">
        <a href="../index.php" class="btn btn-primary">Regresar al Inicio</a>

        <?php if (!isset($_SESSION['id_usuario'])): ?>
            <a href="../logica/login.php" class="btn btn-primary">Iniciar Sesión</a>
        <?php else: ?>
            <span class="btn btn-success disabled">Sesión Activa</span>
        <?php endif; ?>
    </div>

    <h2 class="mb-4 text-center">Libros Disponibles</h2>

    <div class="row">

        <?php while($libro = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">

            <div class="card shadow-sm">

                <!-- IMAGEN DEL LIBRO -->
                <?php if (!empty($libro['imagen'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($libro['imagen']) ?>"
                         class="card-img-top"
                         style="height:260px; object-fit:cover;">
                <?php else: ?>
                    <img src="/libreria/img/libros/reload.jpg"
                         class="card-img-top"
                         style="height:260px; object-fit:cover;">
                <?php endif; ?>

                <div class="card-body">

                    <h5 class="card-title"><?= htmlspecialchars($libro['titulo']) ?></h5>

                    <p class="card-text">
                        <?= $libro['descripcion']
                            ? substr($libro['descripcion'], 0, 120) . "..."
                            : "Sin descripción disponible" ?>
                    </p>

                    <p class="fw-bold text-success">
                        $<?= number_format($libro['precio'], 2) ?>
                    </p>

                    <a href="../comprar/ver.php?id=<?= $libro['id_libro'] ?>"
                       class="btn btn-primary w-100">
                        Ver más
                    </a>

                </div>

            </div>

        </div>
        <?php endwhile; ?>

    </div>

</div>

</body>
</html>
