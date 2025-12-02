<?php
if (!isset($_SESSION)) { session_start(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Librería — Comprar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f5f5; }
        nav { background:#003366; }
        nav a { color:white !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand text-white" href="../index.php">Librería</a>

    <div>
      <?php if(isset($_SESSION['nombre'])): ?>
        <span class="text-white me-3">
            Hola, <?= $_SESSION['nombre']; ?>
        </span>
        <a class="btn btn-danger btn-sm" href="../logica/logout.php">Cerrar sesión</a>
      <?php else: ?>
        <a class="btn btn-light btn-sm" href="../logica/login.php">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container mt-4">
