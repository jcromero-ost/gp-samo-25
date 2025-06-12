<h2>Lista de articulos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>CLAART</th>
      <th>Codigo</th>
      <th>Nombre</th>
      <th class="d-none">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($articulos)): ?>
      <?php foreach ($articulos as $articulo): ?>
        <tr>
          <td><?= htmlspecialchars($articulo['CLAART'] ?? '') ?></td>
          <td><?= htmlspecialchars($articulo['CODIGO'] ?? '') ?></td>
          <td><?= htmlspecialchars($articulo['NOMBRE']) ?></td>
          <td>
            <form method="post" action="<?= BASE_URL ?>/articulos_eliminar" class="d-inline d-none" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
              <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
              <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" class="text-center">No hay articulos</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>
