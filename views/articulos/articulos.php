<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../models/Articulo.php';
require_once __DIR__ . '/../../models/Escandallo.php';
require_once __DIR__ . '/../../models/Colores.php';

$articuloModel = new Articulo();
$escandalloModel = new Escandallo();
$coloresModel = new Colores();

$filtros = [
    'codigo' => $_GET['codigo'] ?? '',
    'nombre' => $_GET['nombre'] ?? '',
];

$limit = isset($_GET['cantidad']) ? max(1, intval($_GET['cantidad'])) : 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json; charset=utf-8');

    $articulos = $articuloModel->getArticulosFiltrados($filtros, $offset, $limit);
    $totalRegistros = $articuloModel->getTotalFiltrado($filtros);
    $totalPaginas = max(1, ceil($totalRegistros / $limit));

    // Añadir conteos
    foreach ($articulos as &$articulo) {
        $codigo = $articulo['CODIGO'];
        $claart = $articulo['CLAART'];
        $articulo['materias_count'] = $escandalloModel->countMateriasPrimas($codigo);
        $articulo['colores_count'] = $coloresModel->countColores($claart);
        //$articulo['articulos_sin_orden_count'] = $articuloModel->countArticulosSinOrden($claart);
    }
    unset($articulo);

    echo json_encode([
        'articulos' => $articulos,
        'page' => $page,
        'totalPaginas' => $totalPaginas,
    ]);
    exit;
}



/*
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$articulos = $articuloModel->getAllArticulos($offset, $limit);

// Aquí añadimos el conteo de materias primas para cada artículo
foreach ($articulos as &$articulo) {
    $codigo = $articulo['CODIGO'];
    $claart = $articulo['CLAART'];
    $articulo['materias_count'] = $escandalloModel->countMateriasPrimas($codigo);
    $articulo['colores_count'] = $coloresModel->countColores($claart);
}
unset($articulo);

$totalRegistros = $articuloModel->getTotal();
$totalPaginas = max(1, ceil($totalRegistros / $limit));

if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json; charset=utf-8');

    // Validar que todos los valores sean UTF-8 válidos
    foreach ($articulos as $i => &$art) {
        foreach ($art as $campo => &$valor) {
            if (is_string($valor) && !mb_check_encoding($valor, 'UTF-8')) {
                error_log("Codificación inválida en artículo {$i}, campo {$campo}");
                $valor = '';
            }
        }
    }
    unset($art); // rompe referencia

    $resultado = [
        'articulos' => $articulos,
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
$view = __DIR__ . '/articulos_content.php';
include __DIR__ . '/../layout.php';
