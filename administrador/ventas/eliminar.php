<?php
require_once "../includes/auth.php";
require_once __DIR__ . '/../../logica/conexion.php';

// Validar ID recibido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location='index.php';</script>";
    exit;
}

$id_venta = intval($_GET['id']);

// Verificar si la venta existe
$check = $conexion->query("SELECT * FROM ventas WHERE id_venta = $id_venta");

if ($check->num_rows === 0) {
    echo "<script>alert('La venta no existe.'); window.location='index.php';</script>";
    exit;
}

// Primero eliminar los detalles de venta (evita error de llave foránea)
$conexion->query("DELETE FROM detalle_ventas WHERE id_venta = $id_venta");

// Ahora eliminar la venta
if ($conexion->query("DELETE FROM ventas WHERE id_venta = $id_venta")) {
    echo "<script>alert('Venta eliminada correctamente.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Error al eliminar la venta: " . $conexion->error . "'); window.location='index.php';</script>";
}
exit;
?>
