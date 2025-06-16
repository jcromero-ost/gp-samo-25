<?php

require_once __DIR__ . '/../../models/Pedido.php';

$pedidoModel = new Pedido();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$pedidos = $pedidoModel->getAllPedidos($offset, $limit);
$totalRegistros = $pedidoModel->getTotal();
$totalPaginas = max(1, ceil($totalRegistros / $limit));

// Si es una petición AJAX, devolvemos JSON con los pedidos y paginación
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json');
    echo json_encode([
        'pedidos' => $pedidos,
        'page' => $page,
        'totalPaginas' => $totalPaginas,
    ]);
    exit;
}

// Si no es AJAX, carga la vista completa como antes
$view = __DIR__ . '/pedidos_content.php';
include __DIR__ . '/../layout.php';
