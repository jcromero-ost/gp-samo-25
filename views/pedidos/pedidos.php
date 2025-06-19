<?php

// Incluye el modelo Pedido, que contiene la lógica para acceder a los datos de pedidos
require_once __DIR__ . '/../../models/Pedido.php';

// Crea una instancia del modelo Pedido
$pedidoModel = new Pedido();

// Obtiene el número de página desde la URL (GET), por defecto 1 si no se especifica
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Define cuántos registros se mostrarán por página
$limit = 10;

// Calcula el offset para la consulta SQL (desde qué registro comenzar)
$offset = ($page - 1) * $limit;

// Obtiene los pedidos de la base de datos usando el modelo, con paginación
$pedidos = $pedidoModel->getAllPedidos($offset, $limit);

// Obtiene el total de registros disponibles
$totalRegistros = $pedidoModel->getTotal();

// Calcula cuántas páginas hay en total, como mínimo 1
$totalPaginas = max(1, ceil($totalRegistros / $limit));

// Si la petición fue hecha por AJAX (fetch o XMLHttpRequest)
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    // Devuelve la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode([
        'pedidos' => $pedidos,         // Lista de pedidos paginados
        'page' => $page,               // Página actual
        'totalPaginas' => $totalPaginas, // Total de páginas
    ]);
    exit; // Termina la ejecución del script para no cargar la vista completa
}

// Si la petición no fue por AJAX, se carga la vista completa
$view = __DIR__ . '/pedidos_content.php'; // Define qué vista parcial se incluirá
include __DIR__ . '/../layout.php';       // Incluye el layout general del sitio
