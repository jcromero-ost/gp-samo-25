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
    
    // Obtén todos los registros y filtra solo los que coincidan
    $filtered = array_values(array_filter(
        $this->reader->getRecords(),
        fn($r) => trim($r['CLAPED']) === $claped
    ));

    // Aplica paginación correctamente sobre el array ya filtrado
    return $this->reader->getFilteredRecordsPaginado('CLAPED', $claped, $offset, $limit);
}


public function getTotalLineasPedido($claped) {
    $claped = trim($claped);
    return count(array_filter($this->reader->getRecords(), fn($r) => trim($r['CLAPED']) === $claped));
}
}
