<div class="m-4">
  <h2 class="titulo mb-4">Lista de usuarios</h2>

  <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>Usuario</th>
          <th>Email</th>
          <th>Alias</th>
          <th>Teléfono</th>
          <th>Fecha de creación</th>
          <th>Departamento</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($usuarios)): ?>
          <?php foreach ($usuarios as $usuario): ?>
            <tr>
            <td>
              <div class="d-flex align-items-center">
                <?php if (!empty($usuario['foto']) && str_starts_with($usuario['foto'], 'data:image')): ?>
                  <img src="<?= $usuario['foto'] ?>" class="rounded-circle me-2" style="height: 40px;">
                <?php else: ?>
                  <img src="./public/images/images_users/<?= $usuario['foto'] ?? 'default.jpeg' ?>" class="rounded-circle me-2" style="height: 40px;">
                <?php endif; ?>

                <div>
                  <strong><?= htmlspecialchars($usuario['nombre']) ?></strong><br>
                  <small class="text-muted"><?= htmlspecialchars($usuario['alias'] ?? '') ?></small>
                </div>
              </div>
            </td>
              <td><?= htmlspecialchars($usuario['email']) ?></td>
              <td><?= htmlspecialchars($usuario['alias']) ?></td>
              <td><?= htmlspecialchars($usuario['telefono']) ?></td>
              <td><?= htmlspecialchars($usuario['fecha_creacion']) ?></td>
              <td><?= htmlspecialchars($usuario['nombre_departamento']) ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-editar" data-id="<?= $usuario['id'] ?>" title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $usuario['id'] ?>" title="Eliminar">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted">No hay usuarios registrados.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal eliminar usuario -->
<?php include_once __DIR__ . '../../components/modals/usuarios/usuarios_eliminar_modal.php'; ?>
<script src="./public/js/usuarios/usuarios_eliminar.js"></script>
