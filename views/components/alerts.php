<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show m-2" role="alert">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show m-2" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['info'])): ?>
    <div class="alert alert-info alert-dismissible fade show m-2" role="alert">
        <?= htmlspecialchars($_SESSION['info']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['info']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show m-2" role="alert">
        <?= htmlspecialchars($_SESSION['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>
<script>
  // Esperar a que el DOM esté completamente cargado
  document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function (alert) {
      // Esperar 3 segundos y luego cerrarlas
      setTimeout(function () {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
      }, 3000); // 3000 ms = 3 segundos
    });
  });
</script>