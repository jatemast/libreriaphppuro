<?php
require_once __DIR__ . "/includes/auth.php";
require __DIR__ . '/../logica/conexion.php';
require_once __DIR__ . "/includes/header.php";

$id = $_GET['id'];

$sql = "
    SELECT l.*, 
           a.nombre AS autor_nombre, 
           a.apellido AS autor_apellido, 
           c.nombre AS categoria_nombre
    FROM libros l
    LEFT JOIN autores a ON l.id_autor = a.id_autor
    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
    WHERE id_libro = $id
";

$libro = $conexion->query($sql)->fetch_assoc();
?>
<link rel="stylesheet" href="/libreria/src/ver_libros.css" />

<script src="/libreria/js/ver_libro.js"></script>

<div class="contenedor-libro fade-in">

    <a href="index.php" class="btn-regresar"> Regresar</a>

    <h2 class="titulo-libro"><?= $libro['titulo'] ?></h2>

    <div class="tarjeta-libro zoom-container">

        <?php if ($libro['imagen']): ?>
            <img 
                src="data:image/jpeg;base64,<?= base64_encode($libro['imagen']) ?>" 
                class="img-libro"
            >
        <?php else: ?>
            <p class="text-muted">Sin imagen disponible</p>
        <?php endif; ?>

        <div class="info-libro">
            <h3><?= $libro['titulo'] ?></h3>

            <p><strong>Autor:</strong> <?= $libro['autor_nombre'] . " " . $libro['autor_apellido'] ?></p>
            <p><strong>Categoría:</strong> <?= $libro['categoria_nombre'] ?></p>
            <p><strong>Editorial:</strong> <?= $libro['editorial'] ?></p>
            <p><strong>Precio:</strong> $<?= $libro['precio'] ?></p>

            <p><?= nl2br($libro['descripcion']) ?></p>

            <form action="carrito.php" method="GET" class="form-carrito">
                <input type="hidden" name="add" value="<?= $libro['id_libro'] ?>">
                
                <label>Cantidad:</label>
                <input type="number" name="cant" min="1" value="1" class="input-cant">

                <button class="btn-agregar">Agregar al carrito</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
