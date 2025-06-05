<?php
require_once 'models/Database.php';

try {
    $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\clientes.dbf";
    $reader = new DBFReader($ruta);
    $clientes = $reader->getRecords();

    echo "<pre>";
    print_r($clientes);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
