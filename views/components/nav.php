<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<nav>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/login' ? 'active' : '' ?>" href="<?= BASE_URL ?>/login">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/usuarios_crear' ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuarios_crear">Crear usuario</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/usuarios' ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuarios">Lista de usuarios</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/departamentos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/departamentos">Lista de departamentos</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/clientes_crear' ? 'active' : '' ?>" href="<?= BASE_URL ?>/clientes_crear">Crear clientes</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/clientes' ? 'active' : '' ?>" href="<?= BASE_URL ?>/clientes">Lista de clientes</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/articulos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/articulos">Lista de articulos</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPath === BASE_URL . '/pedidos' ? 'active' : '' ?>" href="<?= BASE_URL ?>/pedidos">Lista de pedidos</a>
        </li>
    </ul>
</nav>