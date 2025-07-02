<?php

session_start(); // Asegúrate de iniciar la sesión

require_once __DIR__ . '/../../models/Pedido.php';

$pedidoModel = new Pedido();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if (isset($_GET['ejercicio'])) {
    $ejercicio = trim($_GET['ejercicio']);

    if ($ejercicio === '') {
        unset($_SESSION['ejercicio']);
    } else {
        $nuevoEjercicio = intval($ejercicio);
        $_SESSION['ejercicio'] = $nuevoEjercicio;

        // Actualizar en BD si hay usuario logueado
        if (isset($_SESSION['id'])) {
            $pedidoModel->actualizarEjercicioPredeterminado($_SESSION['id'], $nuevoEjercicio);
        }
    }
}


// Obtener el ejercicio activo desde la sesión
$ejercicioSeleccionado = $_SESSION['ejercicio'] ?? null;

// Aplicar filtro por ejercicio si está definido
if ($ejercicioSeleccionado !== null) {
    $pedidos = $pedidoModel->getPedidosPorEjercicio($ejercicioSeleccionado, $offset, $limit);
    $totalRegistros = $pedidoModel->getTotalPorEjercicio($ejercicioSeleccionado);
} else {
    $pedidos = $pedidoModel->getAllPedidos($offset, $limit);
    $totalRegistros = $pedidoModel->getTotal();
}

$totalPaginas = max(1, ceil($totalRegistros / $limit));


// Si la petición fue hecha por AJAX (fetch o XMLHttpRequest)
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json; charset=utf-8');

    // Validar que todos los valores sean UTF-8 válidos
    foreach ($pedidos as $i => &$ped) {
        foreach ($ped as $campo => &$valor) {
            if (!mb_check_encoding($valor, 'UTF-8')) {
                error_log("Codificación inválida en pedido {$i}, campo {$campo}");
                $valor = ''; // limpiar campo para evitar errores en JSON
            }
        }
    }
    unset($ped); // rompe referencia

    $resultado = [
        'pedidos' => $pedidos,
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

// Si la petición no fue por AJAX, se carga la vista completa
$view = __DIR__ . '/pedidos_content.php'; // Define qué vista parcial se incluirá
include __DIR__ . '/../layout.php';       // Incluye el layout general del sitio
