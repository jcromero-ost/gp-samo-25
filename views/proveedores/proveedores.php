<?php

// Incluye el modelo Proveedor, que contiene la lógica para acceder a los datos de proveedores
require_once __DIR__ . '/../../models/Proveedor.php';

// Crea una instancia del modelo Proveedor
$proveedorModel = new Proveedor();


// Recoge filtros desde GET
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = isset($_GET['cantidad']) ? intval($_GET['cantidad']) : 10;
$offset = ($page - 1) * $limit;

$filtros = [
    'codigo' => $_GET['codigo'] ?? '',
    'nombre' => $_GET['nombre'] ?? '',
    'telefono' => $_GET['telefono'] ?? ''
];

// Si el modelo aún no tiene estos métodos, agrégalos (ver más abajo)
$proveedores = $proveedorModel->getProveedoresFiltrados($filtros, $offset, $limit);
$totalRegistros = $proveedorModel->getTotalFiltrado($filtros);
$totalPaginas = max(1, ceil($totalRegistros / $limit));

if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json');
    echo json_encode([
        'proveedores' => $proveedores,
        'page' => $page,
        'totalPaginas' => $totalPaginas,
    ]);
    exit;
}

/*
// Obtiene el número de página desde la URL (GET), por defecto 1 si no se especifica
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Define cuántos registros se mostrarán por página
$limit = 15;

// Calcula el offset para la consulta SQL (desde qué registro comenzar)
$offset = ($page - 1) * $limit;

// Obtiene los proveedores de la base de datos usando el modelo, con paginación
$proveedores = $proveedorModel->getAllProveedores($offset, $limit);

// Obtiene el total de registros disponibles
$totalRegistros = $proveedorModel->getTotal();

// Calcula cuántas páginas hay en total, como mínimo 1
$totalPaginas = max(1, ceil($totalRegistros / $limit));

// Si la petición fue hecha por AJAX (fetch o XMLHttpRequest)
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json; charset=utf-8');

    // Validar que todos los valores sean UTF-8 válidos
    foreach ($proveedores as $i => &$cli) {
        foreach ($cli as $campo => &$valor) {
            if (!mb_check_encoding($valor, 'UTF-8')) {
                error_log("Codificación inválida en proveedor {$i}, campo {$campo}");
                $valor = ''; // limpiar campo para evitar errores en JSON
            }
        }
    }
    unset($cli); // rompe referencia

    $resultado = [
        'proveedores' => $proveedores,
        'page' => $page,
        'totalPaginas' => $totalPaginas,
    ];

    $json = json_encode($resultado);
    if ($json === false) {
        echo json_encode(['error' => 'Error en JSON: ' . json_last_error_msg()]);
        exit;
    }

    echo $json;
    exit;
}
*/
// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/proveedores_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';
