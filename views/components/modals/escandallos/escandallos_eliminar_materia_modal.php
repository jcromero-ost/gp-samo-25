<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modal_eliminar_materia" tabindex="-1" aria-labelledby="modal_eliminar_materiaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <input type="hidden" name="id" id="articulopadre_id_modal">
        <input type="hidden" name="id" id="articulo_id_modal">

        <div class="modal-header">
          <h5 class="modal-title" id="modal_eliminar_materiaLabel">Eliminar materia prima</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p>¿Estás seguro que deseas eliminar la materia prima del articulo <strong id="id_articulo_mostrar"></strong>?</p>
        </div>

        <div class="modal-footer">
          <button id="boton_eliminar_modal" type="button" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>