<?php
session_start();
require __DIR__ . '/../logica/conexion.php';
require_once __DIR__ . "/includes/header.php";

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = []; 
}

//Agregamos productos al carrito
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);

    // Si no existe en el carrito → poner cantidad 1
    if (!isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] = 1;
    } else {
        $_SESSION['carrito'][$id]++; // aumentar cantidad
    }

    header("Location: carrito.php");
    exit;
}


if (isset($_POST['update']) && isset($_POST['cantidad'])) {
    foreach ($_POST['cantidad'] as $id => $cant) {
        $_SESSION['carrito'][$id] = max(1, intval($cant));
    }
    header("Location: carrito.php");
    exit;
}


if (isset($_GET['vaciar']) && $_GET['vaciar'] == 1) {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    unset($_SESSION['carrito'][$id]);
    header("Location: carrito.php");
    exit;
}

$items = $_SESSION['carrito'];
?>

<link rel="stylesheet" href="/libreria/src/carrito.css" />
<script src="/libreria/js/carrito.js"></script>

<a href="index.php" class="btn-regresar">Regresar</a>


<h2 class="mb-4">Carrito de Compras</h2>

<?php if (empty($items)): ?>
    <div class="alert alert-info">No hay libros en el carrito.</div>
    <a href="index.php" class="btn btn-primary">Seguir comprando</a>

<?php else: ?>

    <!-- Botón de vaciar carrito -->
    <a href="carrito.php?vaciar=1" 
       onclick="return confirm('¿Estás seguro de vaciar el carrito?')"
       class="btn btn-danger mb-3">
       Vaciar carrito
    </a>

<form method="POST">

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Título</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Eliminar</th>
        </tr>
    </thead>

    <tbody>
    <?php 
    $total_general = 0;

    foreach ($items as $id => $cantidad): 

        $q = $conexion->query("SELECT * FROM libros WHERE id_libro = $id");
        $libro = $q->fetch_assoc();

        if (!$libro) continue;

        $subtotal = $libro['precio'] * $cantidad;
        $total_general += $subtotal;
    ?>
        <tr>
            <!-- Imagen -->
            <td>
                <?php if ($libro['imagen']): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($libro['imagen']) ?>"
                         width="90" height="120"
                         style="object-fit:cover;border-radius:5px;">
                <?php else: ?>
                    <span class="text-muted">Sin imagen</span>
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($libro['titulo']) ?></td>

            <td>$<?= number_format($libro['precio'], 2) ?></td>

            <!-- Cantidad -->
            <td>
                <input type="number" name="cantidad[<?= $id ?>]" 
                       value="<?= $cantidad ?>" min="1"
                       class="form-control" style="width:80px;">
            </td>

            <!-- Subtotal -->
            <td>$<?= number_format($subtotal, 2) ?></td>

            <td>
                <a href="carrito.php?delete=<?= $id ?>" 
                   onclick="return confirm('¿Eliminar este libro del carrito?');"
                   class="btn btn-sm btn-danger">X</a>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="4" class="text-end">TOTAL GENERAL</th>
            <th colspan="2">$<?= number_format($total_general, 2) ?></th>
        </tr>
    </tfoot>

</table>

<!-- Botones -->
<button name="update" class="btn btn-warning">Actualizar cantidades</button>
<a href="procesar.php" class="btn btn-success">Finalizar compra</a>


</form>

<?php endif; ?>

<?php require_once __DIR__ . "/includes/footer.php"; ?>