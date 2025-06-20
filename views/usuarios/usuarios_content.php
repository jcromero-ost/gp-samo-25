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
              <button type="button" class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $usuario['id'] ?>">Eliminar</button>
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

<!-- Modal eliminar usuario -->
<?php include_once __DIR__ . '../../components/modals/usuarios/usuarios_eliminar_modal.php'; ?>

<script src="./public/js/usuarios/usuarios_eliminar.js"></script>
