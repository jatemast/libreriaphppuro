<?php
session_start();

$_SESSION = [];

session_destroy();


header("Location: ../index.php?logout=1");
exit;
?>
<?php if (isset($_GET['logout'])): ?>
<script>
  alert("Has cerrado sesión correctamente.");
</script>
<?php endif; ?>


