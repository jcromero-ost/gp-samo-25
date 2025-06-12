<?php

require_once __DIR__ . '/../../models/Cliente.php';

// Crea un objeto del modelo
$clienteModel = new Cliente();
$clientes = $clienteModel->getAllClientes(); //Llama a la funcion para mostrar todos los usuarios

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
