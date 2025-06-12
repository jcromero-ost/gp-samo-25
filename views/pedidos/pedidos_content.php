<h2>Lista de pedidos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>CLAPED</th>
      <th>CLAPEMP</th>
      <th>NOMBRE CLIENTE</th>
      <th>LINEAS</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($pedidos)): ?>
      <?php foreach ($pedidos as $pedido): ?>
        <tr>
        <td><?= htmlspecialchars($pedido['CLAPED'] ?? '') ?></td>
        <td><?= htmlspecialchars($pedido['CLAEMP'] ?? '') ?></td>
          <td><?= htmlspecialchars($pedido['NOMCLI']) ?></td>
          <td>
            <button type="button" class="btn btn-sm btn-info btn-ver-lineas" data-id="<?= $pedido['CLAPED'] ?>" data-bs-toggle="modal" data-bs-target="#modal_ver_lineas_pedido">
              Ver Lineas
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" class="text-center">No hay pedidos</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>

<!-- Modal ver lineas -->
<?php include_once __DIR__ . '../../components/modals/pedidos/pedidos_ver_lineas_modal.php'; ?>

<script src="./public/js/pedidos/pedidos_ver_lineas.js"></script>
