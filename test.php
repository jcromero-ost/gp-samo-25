<?php
require_once 'models/Database.php';

try {
    $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\clientes.dbf";
    $reader = new DBFReader($ruta);

    echo "<h3>Estructura del archivo DBF:</h3><pre>";
    print_r($reader->getFields()); // Ahora usamos el método público
    echo "</pre>";

    echo "<h3>Registros:</h3><pre>";
    $clientes = $reader->getRecords();
    print_r($clientes);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

