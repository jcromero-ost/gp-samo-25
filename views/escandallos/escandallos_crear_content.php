<div class="m-4">
  <h2 class="titulo mb-4">Crear escandallo</h2>

  <form class="form-section shadow p-3" method="post" action="<?= BASE_URL ?>/" enctype="multipart/form-data">
    <div class="row align-items-end g-2">
      <div class="col-md-10 position-relative">
        <label for="articulo_search" class="form-label">Buscar artículo</label>
        <input type="text" class="form-control" id="articulo_search" autocomplete="off" required>
        <input type="hidden" name="articulo_codigo" id="articulo_codigo">
        <div id="articulo_suggestions" class="list-group position-absolute mt-1" style="z-index: 1000;"></div>
      </div>
      <div class="col-md-2 d-grid">
        <button id="boton_asignar_materias" type="button" class="btn btn-dark">Asignar Materias Primas</button>
      </div>
    </div>

    <div id="alerta" class="alert fade show d-none" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>

    <div id="contenedor_materias" class="d-none m-4"></div>
  </form>
</div>

<div class="m-4">
  <!--<h2 class="titulo mb-4">Lista de artículos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>-->

  <div id="articulos-container"></div>
</div>

<script src="./public/js/articulos/articulos_paginacion.js"></script>
<script src="./public/js/articulos/articulos_ver_materias.js"></script>
<script src="./public/js/escandallos/escandallos_articulos_sugerencias.js"></script>
<script src="./public/js/escandallos/escandallos.js"></script>


