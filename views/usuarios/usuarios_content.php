<h2>Lista de usuarios ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>Nombre</th>
      <th>Email</th>
      <th>Alias</th>
      <th>Teléfono</th>
      <th>Fecha de creación</th>
      <th>Departamento ID</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($usuarios)): ?>
      <?php foreach ($usuarios as $usuario): ?>
        <tr>
          <td><?= htmlspecialchars($usuario['nombre']) ?></td>
          <td><?= htmlspecialchars($usuario['email']) ?></td>
          <td><?= htmlspecialchars($usuario['alias']) ?></td>
          <td><?= htmlspecialchars($usuario['telefono']) ?></td>
          <td><?= htmlspecialchars($usuario['fecha_creacion']) ?></td>
          <td><?= htmlspecialchars($usuario['departamento_id']) ?></td>
          <td>
            <form method="post" action="<?= BASE_URL ?>/usuarios_eliminar" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
              <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
              <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
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
