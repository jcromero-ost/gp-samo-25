<?php

// Incluye el modelo Cliente, que contiene la lógica para acceder a los datos de clientes
require_once __DIR__ . '/../../models/Cliente.php';

// Crea una instancia del modelo Cliente
$clienteModel = new Cliente();

// Obtiene el número de página desde la URL (GET), por defecto 1 si no se especifica
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Define cuántos registros se mostrarán por página
$limit = 10;

// Calcula el offset para la consulta SQL (desde qué registro comenzar)
$offset = ($page - 1) * $limit;

// Obtiene los clientes de la base de datos usando el modelo, con paginación
$clientes = $clienteModel->getAllClientes($offset, $limit);

// Obtiene el total de registros disponibles
$totalRegistros = $clienteModel->getTotal();

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
        'clientes' => $clientes,         // Lista de clientes paginados
        'page' => $page,               // Página actual
        'totalPaginas' => $totalPaginas, // Total de páginas
    ]);
    exit; // Termina la ejecución del script para no cargar la vista completa
}

/*
$registroValido = null;
$registroNuevo = null;

foreach ($clientes as $reg) {
    if (trim($reg['NOMBRE']) === 'prueba89') {
        $registroValido = $reg;
    }
    if (trim($reg['CODIGO']) === '826155') {  // Cambia aquí al código real del insert
        $registroNuevo = $reg;
    }
}

if (!$registroValido) {
    die("No se encontró el registro válido (prueba89).");
}

if (!$registroNuevo) {
    die("No se encontró el registro nuevo insertado desde PHP.");
}

function compararRegistros($r1, $r2) {
    foreach ($r1 as $campo => $valor1) {
        $valor2 = $r2[$campo] ?? null;
        if ($valor1 !== $valor2) {
            echo "Diferencia en $campo:\n";
            echo "  Registro válido: '".addslashes($valor1)."'\n";
            echo "  Registro PHP:    '".addslashes($valor2)."'\n\n";
        }
    }
}

compararRegistros($registroValido, $registroNuevo);
*/

// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/clientes_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';
