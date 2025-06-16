<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Definición de la clase Pedido, que se encarga de manejar operaciones sobre la tabla 'pedidos'
class LineasPedido {
    private $reader;

    public function __construct() {
        $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\pedidol.dbf";
        $this->reader = new DBFReader($ruta);
    }

public function getLineasPedido($claped, $offset = 0, $limit = 10) {
    $claped = trim($claped);
    $all = array_filter($this->reader->getRecords(), fn($r) => trim($r['CLAPED']) == $claped);
    error_log('CLAPED en filtro: ' . $claped);
    error_log("Listado CLAPED disponibles:");
foreach ($this->reader->getRecords() as $r) {
    error_log("'" . $r['CLAPED'] . "'");
}
    return array_slice(array_values($all), $offset, $limit);
}

public function getTotalLineasPedido($claped) {
    $claped = trim($claped);
    return count(array_filter($this->reader->getRecords(), fn($r) => trim($r['CLAPED']) === $claped));
}
}
