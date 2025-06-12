<h2>Lista de departamentos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

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
              <button type="submit" class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $departamento['id'] ?>">Eliminar</button>
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

<!-- Modal eliminar departamento -->
<?php include_once __DIR__ . '../../components/modals/departamentos/departamentos_eliminar_modal.php'; ?>

<script src="./public/js/departamentos/departamentos_eliminar.js"></script>
