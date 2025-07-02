<?php

// Incluye el modelo Ejercicio, que contiene la lógica para acceder a los datos de ejercicios
require_once __DIR__ . '/../../models/Ejercicio.php';

// Crea una instancia del modelo Ejercicio
$ejercicioModel = new Ejercicio();

// Obtiene el número de página desde la URL (GET), por defecto 1 si no se especifica
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Define cuántos registros se mostrarán por página
$limit = 15;

// Calcula el offset para la consulta SQL (desde qué registro comenzar)
$offset = ($page - 1) * $limit;

// Obtiene los ejercicios de la base de datos usando el modelo, con paginación
$ejercicios = $ejercicioModel->getAllEjerciciosPaginados($offset, $limit);

// Obtiene el total de registros disponibles
$totalRegistros = $ejercicioModel->getTotal();

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
        'ejercicios' => $ejercicios,         // Lista de ejercicios paginados
        'page' => $page,               // Página actual
        'totalPaginas' => $totalPaginas, // Total de páginas
    ]);
    exit; // Termina la ejecución del script para no cargar la vista completa
}

// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/ejercicios_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';
