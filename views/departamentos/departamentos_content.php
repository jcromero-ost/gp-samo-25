<h2>Lista de departamentos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($departamentos)): ?>
      <?php foreach ($departamentos as $departamento): ?>
        <tr>
          <td><?= htmlspecialchars($departamento['id']) ?></td>
          <td><?= htmlspecialchars($departamento['nombre']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" class="text-center">No hay usuarios registrados.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>
