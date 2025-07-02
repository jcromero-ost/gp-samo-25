<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isLoginPage = $currentPath === BASE_URL . '/login';

$inicioActive = (
    $currentPath === BASE_URL . '/inicio'
);

$ejerciciosActive = (
    $currentPath === BASE_URL . '/ejercicios'
);

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
    $currentPath === BASE_URL . '/articulos' ||
    $currentPath === BASE_URL . '/articulos_crear' ||
    $currentPath === BASE_URL . '/escandallos_crear'
);

$pedidosActive = (
    $currentPath === BASE_URL . '/pedidos'
);
?>

<?php if (!$isLoginPage): ?>
<nav class="sidebar-samo navbar navbar-expand-lg navbar-custom mb-2">
  <div class="container-fluid">
<a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= BASE_URL ?>/inicio">
  <img src="<?= BASE_URL ?>/public/images/logo.png" alt="Logo" height="32">
  <span>Brown Jury</span>
</a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainMenu">
        <ul class="navbar-nav me-auto">
            <li class="nav-item <?= $inicioActive ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPath === BASE_URL . '/inicio' ? 'active' : '' ?>" href="<?= BASE_URL ?>/inicio" role="button">
                    Inicio
                </a>
            </li>

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

            <li class="nav-item <?= $ejerciciosActive ? 'active' : '' ?>">
                <a class="nav-link <?= $currentPath === BASE_URL . '/ejercicios' ? 'active' : '' ?>" href="<?= BASE_URL ?>/ejercicios" role="button">
                    Campañas
                </a>
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
                    <li class="d-none">
                        <a class="dropdown-item <?= $currentPath === BASE_URL . '/articulos_crear' ? 'active' : '' ?>" href="<?= BASE_URL ?>/articulos_crear"><i class="bi bi-plus-square me-2"></i>Crear articulos</a>
                    </li>
                    <li>
                        <a class="dropdown-item <?= $currentPath === BASE_URL . '/escandallos_crear' ? 'active' : '' ?>" href="<?= BASE_URL ?>/escandallos_crear"><i class="bi bi-plus-square me-2"></i>Crear escandallo</a>
                    </li>
                    <li>
                        <a class="dropdown-item <?= $currentPath === BASE_URL . '/articulos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/articulos"><i class="bi bi-box-seam me-2"></i>Gestionar articulos</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?= $pedidosActive ? 'active' : '' ?>">
                <a class="nav-link dropdown-toggle <?= $pedidosActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">Pedidos</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li>
                        <a class="dropdown-item <?= $currentPath === BASE_URL . '/pedidos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/pedidos"><i class="bi bi-receipt me-2"></i>Gestionar pedidos</a>
                    </li>
                </ul>
            </li>
        </ul>

        <?php 
            require_once __DIR__ . '/../../models/Ejercicio.php';

            // Crea una instancia del modelo Ejercicio
            $ejercicioModel = new Ejercicio();
            // Obtiene los clientes de la base de datos usando el modelo, con paginación
            $ejercicios = $ejercicioModel->getAllEjercicios();
        ?>
        <!-- En tu header donde está el select -->
        <form id="form-ejercicio" method="GET" action="./pedidos" class="d-flex align-items-center">
        <label for="ejercicio" class="me-2 fw-bold text-white">Ejercicio activo:</label>
        <select name="ejercicio" id="ejercicio" 
            class="form-select me-4 bg-dark text-white" 
            style="width: auto;" 
            onchange="document.getElementById('form-ejercicio').submit();">
            <option value="" <?= !isset($_SESSION['ejercicio']) ? 'selected' : '' ?>>Todos</option>
            <?php foreach ($ejercicios as $ejercicio): ?>
                <option value="<?= htmlspecialchars($ejercicio['CLAEJE']) ?>"
                    <?= (isset($_SESSION['ejercicio']) && $_SESSION['ejercicio'] == $ejercicio['CLAEJE']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ejercicio['CLAEJE']) ?> <?= htmlspecialchars($ejercicio['NOMEJE']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        </form>




      <span class="navbar-text me-2">Usuario: <strong><?php echo $_SESSION['nombre'] ?></strong></span>

      <!-- Foto de perfil con borde primary y enlace -->
      <a href="<?= BASE_URL ?>/perfil_editar" class="d-inline-block rounded-circle me-2" style="height: 40px; width: 40px; overflow: hidden;">
        <?php if (!empty($_SESSION['foto']) && strpos($_SESSION['foto'], 'data:image') === 0): ?>
          <img src="<?= htmlspecialchars($_SESSION['foto']) ?>" 
               alt="Foto de perfil" 
               style="height: 100%; width: 100%; object-fit: cover;">
        <?php else: ?>
          <img src="./public/images/images_users/default.jpeg" 
               alt="Foto por defecto" 
               style="height: 100%; width: 100%; object-fit: cover;">
        <?php endif; ?>
      </a>

      <form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
        <button type="submit" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i>
        </button>
      </form>
    </div>
  </div>
</nav>
<?php endif; ?>
