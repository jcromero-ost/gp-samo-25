<div class="m-4">
  <h2 class="titulo mb-4">Ejercicios</h2>

  <div>
    <!-- Filtros -->
    <form class="row g-3 align-items-end mb-4" onsubmit="return false;">
      <div class="col-md-2">
        <label for="filtrar_codigo" class="form-label">Código</label>
        <input type="text" id="filtrar_codigo" class="form-control">
      </div>

      <div class="col-md-5">
        <label for="filtrar_nombre" class="form-label">Nombre</label>
        <input type="text" id="filtrar_nombre" class="form-control">
      </div>

      <div class="col-md-4">
        <label for="cantidad" class="form-label">Registros por página</label>
        <select id="cantidad" class="form-select">
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>

      <div class="col-md-1 h-100">
        <button id="btn-imprimir" class="btn btn-secondary btn-sm-custom w-100 h-100">
          <i class="bi bi-printer w-100"></i> Imprimir
        </button>
      </div>
    </form>
  </div>

  <div id="ejercicios-container"></div>
</div>

<script src="./public/js/ejercicios/ejercicios.js"></script>
