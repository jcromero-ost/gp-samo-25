<?php
require_once __DIR__ . '/../models/DatabaseLOCAL.php';

$archivos = [
    'articulo'        => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\articulo.dbf',        'script' => 'importar_articulo.php',        'tabla' => 'cg_articulos'],
    'clientes'        => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\clientes.dbf',        'script' => 'importar_cliente.php',         'tabla' => 'cg_clientes'],
    'proveedores'        => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\proveedo.dbf',        'script' => 'importar_proveedor.php',         'tabla' => 'cg_proveedores'],
    'ejercicios'      => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\EJERCIC.dbf',         'script' => 'importar_ejercicio.php',       'tabla' => 'cg_ejercicios'],
    'pedidos'         => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\pedido.dbf',          'script' => 'importar_pedido.php',          'tabla' => 'cg_pedidos'],
    'pedidos_lineas'  => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\pedidol.dbf',         'script' => 'importar_pedido_lineas.php',   'tabla' => 'cg_pedidos_lineas'],
    'colores'  => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\COLORES.dbf',         'script' => 'importar_colores.php',   'tabla' => 'cg_colores'],
    'tallaje'  => ['path' => 'C:\\SAMO\\ClasGes6SP26\\DATOS\\tallajes.dbf',         'script' => 'importar_tallaje.php',   'tabla' => 'cg_tallaje'],
];

$db = DatabaseLOCAL::connect();
$base_dir = __DIR__;
$hash_dir = $base_dir . '/hashes';

if (!is_dir($hash_dir)) {
    mkdir($hash_dir);
}

foreach ($archivos as $clave => $info) {
    $archivo_dbf = $info['path'];
    $tabla       = $info['tabla'];
    $script      = $base_dir . '/' . $info['script'];
    $hash_file   = $hash_dir . "/$clave.hash";

    echo "Verificando '$clave'...\n";

    if (!file_exists($archivo_dbf)) {
        echo "No se encontró el archivo: $archivo_dbf\n";
        continue;
    }

    $hash_actual = md5_file($archivo_dbf);

    // Verificar si la tabla existe y está vacía
    $tabla_vacia = true;
    try {
        $stmt = $db->query("SELECT COUNT(*) as total FROM `$tabla`");
        $result = $stmt->fetch();
        $tabla_vacia = $result['total'] == 0;
    } catch (PDOException $e) {
        echo "ℹTabla '$tabla' no encontrada. Se procederá a crearla.\n";
        $tabla_vacia = true;
    }

    $hash_anterior = file_exists($hash_file) ? trim(file_get_contents($hash_file)) : '';

    if ($hash_actual !== $hash_anterior || $tabla_vacia) {
        echo "Cambios detectados o tabla vacía. Ejecutando '$info[script]'...\n";
        include $script;
        file_put_contents($hash_file, $hash_actual);
    } else {
        echo "Sin cambios en '$clave'.\n";
    }

    echo str_repeat("-", 40) . "\n";
}

echo "Sincronización finalizada.\n";
