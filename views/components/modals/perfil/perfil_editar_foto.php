<div class="modal fade" id="modalEditarFoto" tabindex="-1" aria-labelledby="modalEditarFotoLabel" aria-hidden="true" data-usuario-foto="<?= htmlspecialchars($usuario['foto']) ?>">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formEditarfoto" method="POST" action="<?= BASE_URL ?>/perfil_editar_foto">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
        <input type="hidden" name="foto_base64" id="edit_foto_recortada">

        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarFotoLabel">Editar foto de perfil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <!-- Foto -->
          <div class="mb-3">
            <div id="edit-drop-area" class="border border-secondary rounded p-4 text-center" style="background-color:#f8f9fa; cursor:pointer;">
              <p id="edit-drop-text">Arrastra la imagen aquí o haz clic para seleccionar</p>
              <input type="file" id="edit_foto" class="form-control d-none" accept="image/*">
              <div id="edit-preview-container" class="mt-3 d-none">
                
                <img id="edit_preview" class="img-thumbnail mb-2" style="max-height:300px;">
                <div class="text-end mt-2">
                  <button type="button" id="edit-btn-clear" class="btn btn-sm btn-outline-danger">Quitar imagen</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Aplicar cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="./public/js/perfil/perfil_editar_foto.js"></script>
