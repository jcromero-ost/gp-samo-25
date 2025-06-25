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

  <div id="articulos-container">
    <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>CLAART</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Materias Primas</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($articulos)): ?>
            <?php foreach ($articulos as $i => $articulo): ?>
              <tr>
                <td><?= htmlspecialchars($articulo['CLAART'] ?? '') ?></td>
                <td><?= htmlspecialchars($articulo['CODIGO'] ?? '') ?></td>
                <td><?= htmlspecialchars($articulo['NOMBRE'] ?? '') ?></td>
                <td>
                  <button class="btn btn-sm btn-info toggle-lines-btn show-btn" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseLines<?= $i ?>"
                    aria-expanded="false"
                    aria-controls="collapseLines<?= $i ?>">
                    Ver materias primas
                  </button>

                  <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseLines<?= $i ?>"
                    aria-expanded="true"
                    aria-controls="collapseLines<?= $i ?>">
                    Ocultar materias primas
                  </button>
                </td>
              </tr>
              <tr class="collapse" id="collapseLines<?= $i ?>">
                <td colspan="4">
                  <div class="p-2">
                    <div class="mt-2 materias-content" data-codpadre="<?= htmlspecialchars($articulo['CODIGO']) ?>"></div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center text-muted">No hay artículos registrados.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <nav aria-label="Paginación artículos" class="mt-4">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a href="#" class="page-link" data-page="<?= $page - 1 ?>">Anterior</a>
          </li>
        <?php else: ?>
          <li class="page-item disabled">
            <span class="page-link">Anterior</span>
          </li>
        <?php endif; ?>

        <li class="page-item disabled">
          <span class="page-link">Página <?= $page ?> de <?= $totalPaginas ?></span>
        </li>

        <?php if ($page < $totalPaginas): ?>
          <li class="page-item">
            <a href="#" class="page-link" data-page="<?= $page + 1 ?>">Siguiente</a>
          </li>
        <?php else: ?>
          <li class="page-item disabled">
            <span class="page-link">Siguiente</span>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div>

<script src="./public/js/articulos/articulos_paginacion.js"></script>
<script src="./public/js/articulos/articulos_ver_materias.js"></script>
<script src="./public/js/escandallos/escandallos_articulos_sugerencias.js"></script>
<script src="./public/js/escandallos/escandallos.js"></script>


