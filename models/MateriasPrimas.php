<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Definición de la clase Pedido, que se encarga de manejar operaciones sobre la tabla 'pedidos'
class MateriasPrimas {
    private $reader;

    public function __construct() {
        $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\articulo.dbf";
        $this->reader = new DBFReader($ruta);
    }

    public function getMateriasPrimas($codpadre, $offset = 0, $limit = 10) {
        $codpadre = trim($codpadre);

        // Si no tiene CODPADRE, usar el mismo código como fallback
        if ($codpadre === '') {
            return []; // o puedes lanzar una excepción si eso no es válido
        }
        
        // Obtén todos los registros y filtra solo los que coincidan
        $filtered = array_values(array_filter(
            $this->reader->getRecords(),
            fn($r) => trim($r['CODPADRE']) === $codpadre
        ));

        // Aplica paginación correctamente sobre el array ya filtrado
        return $this->reader->getFilteredRecordsPaginado('CODPADRE', $codpadre, $offset, $limit);
    }


    public function getTotalMateriasPrimas($codpadre) {
        $codpadre = trim($codpadre);
        return count(array_filter($this->reader->getRecords(), fn($r) => trim($r['CODPADRE']) === $codpadre));
    }
}
