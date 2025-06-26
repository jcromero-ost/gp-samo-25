<?php

require_once __DIR__ . '/../../models/Articulo.php';
require_once __DIR__ . '/../../models/Escandallo.php';

$articuloModel = new Articulo();
$escandalloModel = new Escandallo();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$articulos = $articuloModel->getAllArticulos($offset, $limit);

// Aquí añadimos el conteo de materias primas para cada artículo
foreach ($articulos as &$articulo) {
    $codigo = $articulo['CODIGO'];
    // Llamamos a una función que solo cuenta las materias primas (ver más abajo)
    $articulo['materias_count'] = $escandalloModel->countMateriasPrimas($codigo);
}
unset($articulo);

$totalRegistros = $articuloModel->getTotal();
$totalPaginas = max(1, ceil($totalRegistros / $limit));

if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json');
    echo json_encode([
        'articulos' => $articulos,
        'page' => $page,
        'totalPaginas' => $totalPaginas,
    ]);
    exit;
}

$view = __DIR__ . '/articulos_content.php';
include __DIR__ . '/../layout.php';
