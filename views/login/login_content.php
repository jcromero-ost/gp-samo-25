<style>
  body {
    margin: 0;
    padding: 0;
    background: url('<?= BASE_URL ?>/public/images/fondo_login.jpg') no-repeat center center fixed;
    background-size: cover;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', sans-serif;
    color: #f8f9fa;
  }
</style>

<div class="login-container">
  <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1rem;">
    <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo" style="height: 32px;">
    <span style="font-weight: bold; font-size: 1.2rem;">Brown Jury</span>
  </div>
  <h3 class="mb-1 text-center">Iniciar sesión</h3>
  <?php include __DIR__ . '../../components/alerts.php'; ?>
  <form action="<?= BASE_URL ?>/login" method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Usuario</label>
      <input type="text" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Contraseña</label>
      <input type="password" name="passwd" id="passwd" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-login text-center"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar</button>
  </form>
</div>



