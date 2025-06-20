<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isLoginPage = $currentPath === BASE_URL . '/login';

$usuariosActive = (
    $currentPath === BASE_URL . '/usuarios_crear' ||
    $currentPath === BASE_URL . '/usuarios' ||
    $currentPath === BASE_URL . '/departamentos'
);

$clientesActive = (
    $currentPath === BASE_URL . '/clientes_crear' ||
    $currentPath === BASE_URL . '/clientes'
);

$articulosActive = (
    $currentPath === BASE_URL . '/articulos'
);

$pedidosActive = (
    $currentPath === BASE_URL . '/pedidos'
);
?>

<?php if (!$isLoginPage): ?>
<nav class="sidebar-samo navbar navbar-expand-lg navbar-custom mb-2">
  <div class="container-fluid">
<a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
  <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo" height="32">
  <span>Brown Jury</span>
</a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item dropdown <?= $usuariosActive ? 'active' : '' ?>">
            <a class="nav-link dropdown-toggle <?= $usuariosActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">Usuarios</a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li>
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/usuarios_crear' ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuarios_crear"><i class="bi bi-person-plus me-2"></i>Crear usuario</a>
                </li>

                <li>
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/usuarios' ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuarios"><i class="bi bi-person-lines-fill me-2"></i>Gestionar usuarios</a>
                </li>

                <li>
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/departamentos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/departamentos"><i class="bi bi-diagram-3 me-2"></i>Gestionar departamentos</a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown <?= $clientesActive ? 'active' : '' ?>">
            <a class="nav-link dropdown-toggle <?= $clientesActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">Clientes</a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li class="d-none">
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/clientes_crear' ? 'active' : '' ?>" href="<?= BASE_URL ?>/clientes_crear">Crear clientes</a>
                </li>

                <li>
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/clientes' ? 'active' : '' ?>" href="<?= BASE_URL ?>/clientes"><i class="bi bi-person-lines-fill me-2"></i>Gestionar clientes</a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown <?= $articulosActive ? 'active' : '' ?>">
            <a class="nav-link dropdown-toggle <?= $articulosActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">Articulos</a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li>
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/articulos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/articulos"><i class="bi bi-tags me-2"></i>Gestionar articulos</a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown <?= $pedidosActive ? 'active' : '' ?>">
            <a class="nav-link dropdown-toggle <?= $pedidosActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">Pedidos</a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li>
                    <a class="dropdown-item <?= $currentPath === BASE_URL . '/pedidos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/pedidos"><i class="bi bi-box-seam me-2"></i>Gestionar pedidos</a>
                </li>
            </ul>
        </li>
      </ul>
      <span class="navbar-text me-3">Usuario: <strong><?php echo $_SESSION['nombre'] ?></strong></span>

      <form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
        <button type="submit" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>
    </div>
  </div>
</nav>
<?php endif; ?>
