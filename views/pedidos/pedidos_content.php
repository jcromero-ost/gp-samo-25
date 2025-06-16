<h2>Lista de pedidos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>CLAPED</th>
      <th>CLAEMP</th>
      <th>NOMBRE CLIENTE</th>
      <th>LINEAS</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pedidos as $i => $pedido): ?>
      <tr>
        <td><?= htmlspecialchars($pedido['CLAPED']) ?></td>
        <td><?= htmlspecialchars($pedido['CLAEMP']) ?></td>
        <td><?= htmlspecialchars($pedido['NOMCLI']) ?></td>
        <td>
          <button class="btn btn-sm btn-info toggle-lines-btn show-btn" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapseLines<?= $i ?>"
            aria-expanded="false"
            aria-controls="collapseLines<?= $i ?>">
            Ver líneas
          </button>

          <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapseLines<?= $i ?>"
            aria-expanded="true"
            aria-controls="collapseLines<?= $i ?>">
            Ocultar líneas
          </button>
        </td>
      </tr>
      <tr class="collapse" id="collapseLines<?= $i ?>">
        <td colspan="4">
          <div class="p-2">
            <div class="mt-2 lineas-content" data-claped="<?= htmlspecialchars($pedido['CLAPED']) ?>"></div>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<?php if ($page > 1): ?>
  <a href="?page=<?= $page - 1 ?>">Anterior</a>
<?php endif; ?>

<span>Página <?= $page ?> de <?= $totalPaginas ?></span>

<?php if ($page < $totalPaginas): ?>
  <a href="?page=<?= $page + 1 ?>">Siguiente</a>
<?php endif; ?>

<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>

<!-- Modal ver lineas -->
<?php include_once __DIR__ . '../../components/modals/pedidos/pedidos_ver_lineas_modal.php'; ?>

<script src="./public/js/pedidos/pedidos_ver_lineas.js"></script>


