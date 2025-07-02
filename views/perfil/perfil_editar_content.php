<div class="m-4">
  <div class="d-flex align-items-center mb-3 position-relative">
    <div class="position-relative" style="height: 80px; width: 80px;">
      <?php if (!empty($_SESSION['foto']) && strpos($_SESSION['foto'], 'data:image') === 0): ?>
        <img src="<?= htmlspecialchars($_SESSION['foto']) ?>" 
            alt="Foto de perfil" 
            class="rounded-circle"
            style="height: 100%; width: 100%; object-fit: cover;">
      <?php else: ?>
        <img src="./public/images/images_users/default.jpeg" 
            alt="Foto por defecto" 
            class="rounded-circle"
            style="height: 100%; width: 100%; object-fit: cover;">
      <?php endif; ?>

      <!-- Botón con ícono de cámara -->
      <button id="boton_editar_foto" class="btn btn-sm btn-primary-custom position-absolute bottom-0 end-0 rounded-circle" 
              style="transform: translate(30%, 30%);"
              title="Cambiar foto de perfil">
        <i class="bi bi-camera-fill" style="font-size: 0.9rem;"></i>
      </button>
    </div>

    <div class="ms-4">
      <h2 class="titulo mb-0">Mi Perfil</h2>
    </div>
  </div>

  <!-- Información Personal -->
  <div class="row mb-4">
    <form action="<?= BASE_URL ?>/perfil_editar_datos" method="POST" enctype="multipart/form-data">
      <?php $usuarios = $usuario; ?>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="edit_nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="edit_nombre" name="edit_nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
          </div>
          <div class="col-md-6">
            <label for="edit_alias" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" id="edit_alias" name="edit_alias" value="<?= htmlspecialchars($usuario['alias']) ?>">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="edit_email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="edit_email" name="edit_email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
          </div>
          <div class="col-md-6">
            <label for="edit_telefono" class="form-label">Teléfono / Extensión</label>
            <input type="text" class="form-control" id="edit_telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" name="edit_telefono">
          </div>
        </div>
      <button type="submit" class="btn btn-primary-custom">Guardar cambios</button>
    </form>
  </div>

  <!-- Cambiar contraseña -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark text-white">
          Cambiar contraseña
        </div>
        <div class="card-body">
          <form action="<?= BASE_URL ?>/perfil_editar_password" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="new_password" class="form-label">Nueva Contraseña</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="new_password" name="new_password" required>
                  <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye-slash" id="iconPassword"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="new_password_confirm" class="form-label">Repetir Contraseña</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required>
                  <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword_confirm" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye-slash" id="iconConfirm"></i>
                  </button>
                </div>
                <div id="passwordHelp" class="form-text text-danger d-none">Las contraseñas no coinciden.</div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary-custom">Cambiar contraseña</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Foto -->
  <?php include_once __DIR__ . '../../components/modals/perfil/perfil_editar_foto.php'; ?>
</div>
<script src="./public/js/perfil/perfil_editar.js" defer></script>
<script src="./public/js/usuarios/cropper_util.js"></script>

