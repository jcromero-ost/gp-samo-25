<div class="m-4">
  <h2 class="titulo mb-4">Lista de clientes</h2>

  <div id="clientes-container">
    <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>CLACLI</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Localidad</th>
            <th>Provincia</th>
            <th>Postal</th>
            <th>País</th>
            <th>Teléfono</th>
            <th class="d-none">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($clientes)): ?>
            <?php foreach ($clientes as $cliente): ?>
              <tr>
                <td><?= htmlspecialchars($cliente['CLACLI']) ?></td>
                <td><?= htmlspecialchars($cliente['CODIGO']) ?></td>
                <td><?= htmlspecialchars($cliente['NOMBRE']) ?></td>
                <td><?= htmlspecialchars($cliente['DIRECCION'] ?? '') ?></td>
                <td><?= htmlspecialchars($cliente['LOCALIDAD'] ?? '') ?></td>
                <td><?= htmlspecialchars($cliente['PROVINCIA'] ?? '') ?></td>
                <td><?= htmlspecialchars($cliente['POSTAL'] ?? '') ?></td>
                <td><?= htmlspecialchars($cliente['PAIS'] ?? '') ?></td>
                <td><?= htmlspecialchars($cliente['TELEFONO'] ?? '') ?></td>
                <td class="d-none">
                  <form method="post" action="<?= BASE_URL ?>/clientes_eliminar" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                    <input type="hidden" name="codigo" value="<?= htmlspecialchars($cliente['CODIGO']) ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="10" class="text-center text-muted">No hay clientes registrados.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <nav aria-label="Paginación clientes" class="mt-4">
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

<script src="./public/js/clientes/clientes_paginacion.js"></script>
