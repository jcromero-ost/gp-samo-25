<div class="m-4">
  <h2 class="titulo mb-4">Crear Usuario</h2>
  <form class="form-section shadow" method="post" action="<?= BASE_URL ?>/usuarios_crear" enctype="multipart/form-data">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre">
      </div>

      <div class="col-md-6">
        <label for="alias" class="form-label">Alias</label>
        <input type="text" class="form-control" id="alias" name="alias">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
      </div>
      <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="tel" class="form-control" id="telefono" name="telefono">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-12">
        <label for="departamento_id" class="form-label">Departamento</label>
        <select name="departamento_id" id="departamento_id" class="form-select" required>
          <option value="">Seleccionar Departamento...</option>
          <?php foreach ($departamentos as $departamento): ?>
            <option value="<?= $departamento['id'] ?>"><?= htmlspecialchars($departamento['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

		<div class="mb-3">
			<label for="foto" class="form-label">Foto de perfil</label>
			<div id="drop-area" class="border border-secondary rounded p-4 text-center" style="background-color:#f8f9fa; cursor:pointer;">
				<p id="drop-text">Arrastra la imagen aquí o haz clic para seleccionar</p>
				<input type="file" id="foto" class="form-control d-none" accept="image/*">
				<div id="preview-container" class="mt-3 d-none">
					<img id="preview" class="img-thumbnail mb-2" style="max-height:300px;">
					<hr>
					<div class="text-end">
						<button type="button" id="btn-clear" class="btn btn-sm btn-outline-danger">Quitar imagen</button>
					</div>
				</div>
			</div>
			<input type="hidden" name="foto_recortada" id="foto_recortada">
		</div>

		<div class="row mb-3">
			<div class="col-md-6">
				<label for="password" class="form-label">Contraseña</label>
				<div class="input-group">
					<input type="password" class="form-control" id="password" name="password" required>
					<button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Mostrar contraseña">
						<i class="bi bi-eye-slash" id="iconPassword"></i>
					</button>
				</div>
			</div>
			<div class="col-md-6">
				<label for="confirm_password" class="form-label">Repetir contraseña</label>
				<div class="input-group">
					<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
					<button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
						<i class="bi bi-eye-slash" id="iconConfirm"></i>
					</button>
				</div>
				<div id="passwordHelp" class="form-text text-danger d-none">Las contraseñas no coinciden.</div>
			</div>
		</div>

    <button type="submit" class="btn btn-dark">Guardar Usuario</button>
  </form>
</div>


