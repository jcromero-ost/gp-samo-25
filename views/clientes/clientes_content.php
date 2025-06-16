<h2>Lista de clientes ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<div id="clientes-container">
  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>CLACLI</th>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Direccion</th>
        <th>Localidad</th>
        <th>Provincia</th>
        <th>Postal</th>
        <th>Pais</th>
        <th>Telefono</th>
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
            <td>
              <form method="post" action="<?= BASE_URL ?>/clientes_eliminar" class="d-inline d-none" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                <input type="hidden" name="codigo" value="<?= htmlspecialchars($cliente['CODIGO']) ?>">
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">No hay clientes</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <nav aria-label="Paginación clientes">
    <ul class="pagination">
      <?php if ($page > 1): ?>
        <li class="page-item"><a href="#" class="page-link" data-page="<?= $page - 1 ?>">Anterior</a></li>
      <?php else: ?>
        <li class="page-item disabled"><span class="page-link">Anterior</span></li>
      <?php endif; ?>

      <li class="page-item disabled"><span class="page-link">Página <?= $page ?> de <?= $totalPaginas ?></span></li>

      <?php if ($page < $totalPaginas): ?>
        <li class="page-item"><a href="#" class="page-link" data-page="<?= $page + 1 ?>">Siguiente</a></li>
      <?php else: ?>
        <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>

<script src="./public/js/clientes/clientes_paginacion.js"></script> 
