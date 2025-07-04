<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/DepartamentoController.php';
require_once __DIR__ . '/../config/config.php'; // Carga la configuración, como BASE_URL u otras constantes.

// Ruta para la raíz del sitio ('/')
$router->get('/', function () {
    session_start(); // Inicia o continúa la sesión PHP

    // Verifica si el usuario está autenticado
    if (isset($_SESSION['user'])) {
        // Si está autenticado, redirige al dashboard
        header('Location:' . BASE_URL . '/usuarios_crear');
    } else {
        // Si no está autenticado, redirige a la página de login
        header('Location:' . BASE_URL . '/login');
    }
    exit(); // Finaliza la ejecución del script después de la redirección
});

// Ruta para la página de login
$router->get('/login', function () {
    // Carga la vista de login
    require __DIR__ . '/../views/login/login.php';
});
$router->post('/login', 'AuthController@login'); //Ruta para el login

// Ruta para DESLOGUEARTE
$router->get('/logout', function () {
    // Carga la vista de login
    require __DIR__ . '/../views/login/login.php';
});
$router->post('/logout', 'AuthController@logout'); //Ruta para el logout


// Ruta para la página de crear usuario
$router->get('/usuarios_crear', function () {
    // Carga la vista de crear usuarios
    require __DIR__ . '/../views/usuarios/usuarios_crear.php';
});
$router->post('/usuarios_crear', 'UsuarioController@store'); //Ruta para crear un usuario
$router->post('/usuarios_eliminar', 'UsuarioController@delete'); //Ruta para eliminar un usuario

// Ruta para la página de lista de usuarios
$router->get('/usuarios', function () {
    // Carga la vista de crear usuarios
    require __DIR__ . '/../views/usuarios/usuarios.php';
});

// Ruta para la página de lista de departamentos
$router->get('/departamentos', function () {
    // Carga la vista de crear usuarios
    require __DIR__ . '/../views/departamentos/departamentos.php';
});
$router->post('/departamentos_crear', 'DepartamentoController@store'); //Ruta para crear un departamento
$router->post('/departamentos_eliminar', 'DepartamentoController@delete'); //Ruta para eliminar un departamento

// Ruta para la página de lista de clientes
$router->get('/clientes', function () {
    // Carga la vista de clientes
    require __DIR__ . '/../views/clientes/clientes.php';
});

// Ruta para la página de crear clientes
$router->get('/clientes_crear', function () {
    // Carga la vista de clientes
    require __DIR__ . '/../views/clientes/clientes_crear.php';
});
$router->post('/clientes_crear', 'ClienteController@store'); //Ruta para crear un cliente
$router->post('/clientes_eliminar', 'ClienteController@delete'); //Ruta para eliminar un cliente

// Ruta para la página de lista de articulos
$router->get('/articulos', function () {
    // Carga la vista de articulos
    require __DIR__ . '/../views/articulos/articulos.php';
});

// Ruta para la página de lista de pedidos
$router->get('/pedidos', function () {
    // Carga la vista de pedidos
    require __DIR__ . '/../views/pedidos/pedidos.php';
});
$router->post('/pedidos_ver_lineas', 'LineasPedidoController@ver_lineas');


