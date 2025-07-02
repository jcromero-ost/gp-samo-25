<div class="m-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="titulo">Lista de usuarios</h2>
    <a href="<?= BASE_URL ?>/usuarios_crear" class="btn btn-success">
      <i class="bi bi-person-plus-fill"></i> Crear nuevo usuario
    </a>
  </div>

  <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>Usuario</th>
          <th>Email</th>
          <th>Nombre de usuario</th>
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
              <td>
                <?= (new DateTime($usuario['fecha_creacion']))->format('d-m-Y') ?>
              </td>
              <td><?= htmlspecialchars($usuario['nombre_departamento']) ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-editar" data-usuario='<?= json_encode($usuario, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>' data-id="<?= $usuario['id'] ?>" title="Editar">
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

<!-- Modal editar usuario -->
<?php include_once __DIR__ . '../../components/modals/usuarios/usuarios_editar_modal.php'; ?>

<!-- Modal eliminar usuario -->
<?php include_once __DIR__ . '../../components/modals/usuarios/usuarios_eliminar_modal.php'; ?>

<script src="./public/js/usuarios/cropper_util.js"></script>
<script src="./public/js/usuarios/usuarios_editar.js"></script>
<script src="./public/js/usuarios/usuarios_eliminar.js"></script>
