<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modal_editar_usuario" tabindex="-1" aria-labelledby="modal_editar_usuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="<?= BASE_URL ?>/usuarios_editar">
        <input type="hidden" name="id" id="edit_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modal_editar_usuarioLabel">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
            <div class="col-md-6">
                <label for="edit_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="edit_nombre" name="edit_nombre" required>
            </div>

            <div class="col-md-6">
                <label for="edit_alias" class="form-label">Alias</label>
                <input type="text" class="form-control" id="edit_alias" name="edit_alias" required>
            </div>
            </div>

            <div class="row mb-3">
            <div class="col-md-6">
                <label for="edit_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit_email" name="edit_email" required>
            </div>
            <div class="col-md-6">
                <label for="edit_telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="edit_telefono" name="edit_telefono" required>
            </div>
            </div>

            <div class="row mb-3">
            <div class="col-md-12">
                <label for="edit_departamento_id" class="form-label">Departamento</label>
                <select name="edit_departamento_id" id="edit_departamento_id" class="form-select" required>
                <option value="">Seleccionar Departamento...</option>
                <?php foreach ($departamentos as $departamento): ?>
                    <option value="<?= $departamento['id'] ?>"><?= htmlspecialchars($departamento['nombre']) ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            </div>

            <div class="mb-3">
                <label for="edit_foto" class="form-label">Foto de perfil</label>
                <div id="edit-drop-area" class="border border-secondary rounded p-4 text-center" style="background-color:#f8f9fa; cursor:pointer;">
                    <p id="edit-drop-text">Arrastra la imagen aquí o haz clic para seleccionar</p>
                    <input type="file" id="edit_foto" class="form-control d-none" accept="image/*">
                    <div id="edit-preview-container" class="mt-3 d-none">
                        <img id="edit_preview" name="edit_preview" class="img-thumbnail mb-2" style="max-height:300px;">
                        <hr>
                        <div class="text-end">
                            <button type="button" id="edit-btn-clear" class="btn btn-sm btn-outline-danger">Quitar imagen</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="edit_foto_recortada" id="edit_foto_recortada">
            </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar Cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>