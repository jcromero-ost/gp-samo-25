<h2>Lista de departamento ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<form method="post" action="<?= BASE_URL ?>/departamentos_crear">
  <div class="form-group">
    <label for="nombre">Crear departamento</label>
    <input 
      type="text" 
      class="form-control" 
      id="nombre" 
      name="nombre" 
      placeholder="Nombre del departamento" 
    >
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($departamentos)): ?>
      <?php foreach ($departamentos as $departamento): ?>
        <tr>
          <td><?= htmlspecialchars($departamento['id']) ?></td>
          <td><?= htmlspecialchars($departamento['nombre']) ?></td>
          <td>
            <form method="post" action="<?= BASE_URL ?>/departamentos_eliminar" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este departamento?');">
              <input type="hidden" name="id" value="<?= htmlspecialchars($departamento['id']) ?>">
              <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" class="text-center">No hay departamentos registrados.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>
