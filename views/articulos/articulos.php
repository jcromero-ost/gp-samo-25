<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$view = __DIR__ . '/articulos_content.php';
include __DIR__ . '/../layout.php';
