<div class="m-4">
  <h2 class="titulo mb-4">Crear Departamento</h2>

<button id="toggleBtn" class="btn btn-success">
  <i class="bi bi-caret-down-square"></i> Crear nuevo departamento
</button>

<div id="formulario_crear_departamento" style="display:none;">
  <form class="form-section shadow p-3 mt-3" method="post" action="<?= BASE_URL ?>/departamentos_crear">
    <div class="row align-items-end g-2">
      <div class="col-md-10">
        <label for="nombre" class="form-label">Nombre del departamento</label>
        <input type="text" class="form-control" id="nombre" name="nombre">
      </div>
      <div class="col-md-2 d-grid">
        <label class="form-label invisible">Guardar</label>
        <button type="submit" class="btn btn-dark">Guardar Departamento</button>
      </div>
    </div>
  </form>
</div>
</div>

<div class="m-4">
  <!-- <h2 class="titulo mb-4">Lista de Departamentos</h2> -->

  <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($departamentos)): ?>
          <?php foreach ($departamentos as $departamento): ?>
            <tr>
              <td><?= htmlspecialchars($departamento['id']) ?></td>
              <td><?= htmlspecialchars($departamento['nombre']) ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-editar" data-id="<?= $departamento['id'] ?>" title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $departamento['id'] ?>" title="Eliminar">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center text-muted">No hay departamentos registrados.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<!-- Modal eliminar departamento -->
<?php include_once __DIR__ . '../../components/modals/departamentos/departamentos_eliminar_modal.php'; ?>

<script src="./public/js/departamentos/departamentos_eliminar.js"></script>
<script src="./public/js/departamentos/departamentos.js"></script>
