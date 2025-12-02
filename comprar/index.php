<?php
require_once __DIR__ . "/includes/auth.php";
require __DIR__ . '/../logica/conexion.php';
require_once __DIR__ . "/includes/header.php";

// --------- BUSCADOR Y FILTROS ---------
$buscar = $_GET['buscar'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$autor = $_GET['autor'] ?? '';

$query = "
    SELECT l.*, 
           a.nombre AS autor_nombre, 
           a.apellido AS autor_apellido, 
           c.nombre AS categoria
    FROM libros l
    LEFT JOIN autores a ON l.id_autor = a.id_autor
    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
    WHERE 1
";

if ($buscar !== "") {
    $buscar = $conexion->real_escape_string($buscar);
    $query .= " AND (l.titulo LIKE '%$buscar%' OR a.nombre LIKE '%$buscar%' OR a.apellido LIKE '%$buscar%')";
}

if ($categoria !== "") {
    $query .= " AND l.id_categoria = $categoria";
}

if ($autor !== "") {
    $query .= " AND l.id_autor = $autor";
}

$libros = $conexion->query($query);

// Obtener listas para filtros
$categorias = $conexion->query("SELECT * FROM categorias");
$autores = $conexion->query("SELECT * FROM autores");
?>

<h2 class="mb-4">Catálogo de Libros</h2>

<!-- BUSCADOR -->
<form class="row mb-4">
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar libro o autor..."
               value="<?= htmlspecialchars($buscar) ?>">
    </div>

    <div class="col-md-3">
        <select name="categoria" class="form-control">
            <option value="">Todas las categorías</option>
            <?php while($c = $categorias->fetch_assoc()): ?>
                <option value="<?= $c['id_categoria'] ?>"
                    <?= $categoria == $c['id_categoria'] ? "selected" : "" ?>>
                    <?= $c['nombre'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="autor" class="form-control">
            <option value="">Todos los autores</option>
            <?php while($a = $autores->fetch_assoc()): ?>
                <option value="<?= $a['id_autor'] ?>"
                    <?= $autor == $a['id_autor'] ? "selected" : "" ?>>
                    <?= $a['nombre'] . " " . $a['apellido'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>


<!--  GRID DE LIBROS -->
<div class="row">
<?php while($l = $libros->fetch_assoc()): ?>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card shadow-sm">

            <?php if (!empty($l['imagen'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($l['imagen']) ?>"
                     style="height: 200px; object-fit:cover;"
                     class="card-img-top">
            <?php else: ?>
                <div style="height:200px; background:#ddd; display:flex; align-items:center; justify-content:center;">
                    <span class="text-muted">Sin imagen</span>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?= $l['titulo'] ?></h5>
                <p><strong>Autor:</strong> <?= $l['autor_nombre'] . " " . $l['autor_apellido'] ?></p>
                <p><strong>Precio:</strong> $<?= $l['precio'] ?></p>

                <a href="ver.php?id=<?= $l['id_libro'] ?>" class="btn btn-sm btn-outline-info w-100 mb-2">Ver detalles</a>
                <a href="carrito.php?add=<?= $l['id_libro'] ?>" class="btn btn-sm btn-primary w-100">Agregar al carrito</a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>

