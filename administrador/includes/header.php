<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Admin - Librería</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php">Librería - Admin</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
      <a class="btn btn-sm btn-outline-light" href="../logica/logout.php">Cerrar sesión</a>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
