<div class="m-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="titulo">Pedidos</h2>
      <form id="form-orden-fabricacion" method="GET" action="./pedidos" class="d-flex align-items-center">
          <select name="orden_fabricacion_select" id="orden_fabricacion_select" class="form-select me-4" style="width: auto;" onchange="document.getElementById('form-orden-fabricacion').submit();">
              <option value="" <?= !isset($_GET['orden_fabricacion_select']) || $_GET['orden_fabricacion_select'] === '' ? 'selected' : '' ?>>Todos</option>
              <option value="con" <?= (isset($_GET['orden_fabricacion_select']) && $_GET['orden_fabricacion_select'] === 'con') ? 'selected' : '' ?>>Con orden de fabricación</option>
              <option value="sin" <?= (isset($_GET['orden_fabricacion_select']) && $_GET['orden_fabricacion_select'] === 'sin') ? 'selected' : '' ?>>Sin orden de fabricación</option>
          </select>
      </form>
    </div>

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

    <div id="pedidos-container"></div>
</div>

<!-- Modal ver lineas -->
<?php include_once __DIR__ . '../../components/modals/pedidos/pedidos_ver_lineas_modal.php'; ?>

<script src="./public/js/pedidos/pedidos_ver_lineas.js"></script>
<script src="./public/js/pedidos/pedidos.js"></script>  <!-- Nuevo archivo JS para paginación -->
