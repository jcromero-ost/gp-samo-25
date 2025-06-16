<?php

require_once __DIR__ . '/../../models/Cliente.php';

// Crea un objeto del modelo
$clienteModel = new Cliente();

// Obtiene el número de página actual desde la URL (por ejemplo, ?page=2). Si no se especifica, usa la página 1 por defecto. Además, se asegura de que el número sea al menos 1 (no permite 0 o negativos).
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Define cuántos registros mostrar por página.
$limit = 10;

// Calcula el índice del primer registro que debe mostrarse en esta página. Por ejemplo, si estás en la página 3 y hay 10 por página, el offset es 20 (es decir, empieza en el registro 21).
$offset = ($page - 1) * $limit;

// Obtiene los registros de clientes desde el modelo, pasando el offset y el límite. Esto asegura que solo se obtengan los registros necesarios para esta página.
$clientes = $clienteModel->getAllClientes($offset, $limit);

// Obtiene el total de registros disponibles en el archivo DBF (sin filtrar).
$totalRegistros = $clienteModel->getTotal(); // Internamente llama a $reader->getRecordCount()

// Calcula cuántas páginas habrá en total, dividiendo el total de registros entre el límite por página. La función ceil redondea hacia arriba para cubrir registros restantes (por ejemplo, si hay 101 registros, habrá 11 páginas).
$totalPaginas = ceil($totalRegistros / $limit);










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
