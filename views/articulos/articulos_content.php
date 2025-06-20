<h2>Lista de articulos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>
<div id="articulos-container">
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

<script src="./public/js/articulos/articulos_paginacion.js"></script> 

