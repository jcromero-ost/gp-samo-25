<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modal_eliminar_usuario" tabindex="-1" aria-labelledby="modal_eliminar_usuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="<?= BASE_URL ?>/usuarios_eliminar">
        <input type="hidden" name="id" id="delete_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modal_eliminar_usuarioLabel">Eliminar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p>¿Estás seguro que deseas eliminar el usuario con id <strong id="delete_id_mostrar"></strong>?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>