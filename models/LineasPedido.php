<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Pedido, que se encarga de manejar operaciones sobre la tabla 'pedidos'
class LineasPedido {
    private $db; // Propiedad para manejar el lector de archivos DBF

    // Constructor: inicializa el lector DBF apuntando al archivo 'articulo.dbf'
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

public function getLineasPedido($claped, $offset = 0, $limit = 10, $orden = '')
{
    $filtroOrden = '';
    if ($orden === 'con') {
        $filtroOrden = "AND p.COMENT IS NOT NULL AND p.COMENT <> ''";
    } elseif ($orden === 'sin') {
        $filtroOrden = "AND (p.COMENT IS NULL OR p.COMENT = '')";
    }

    $stmt = $this->db->prepare("
        SELECT p.*, c.COLOR as COLOR_NOMBRE
        FROM cg_pedidos_lineas p
        LEFT JOIN cg_colores c ON c.CLACOL = p.CLACOL
        WHERE p.CLAPED = :claped 
          AND p.CODIGO <> ''
          $filtroOrden
        ORDER BY p.CLAPEDL ASC 
        LIMIT :offset, :limit
    ");

    $stmt->bindValue(':claped', trim($claped), PDO::PARAM_STR);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getTotalLineasPedido($claped, $orden = '') {
    $filtroOrden = '';
    if ($orden === 'con') {
        $filtroOrden = "AND COMENT IS NOT NULL AND COMENT <> ''";
    } elseif ($orden === 'sin') {
        $filtroOrden = "AND (COMENT IS NULL OR COMENT = '')";
    }

    $stmt = $this->db->prepare("
        SELECT COUNT(*) as total 
        FROM cg_pedidos_lineas 
        WHERE CLAPED = :claped
          AND CODIGO IS NOT NULL  
          AND CODIGO <> ''
          $filtroOrden
    ");
    $stmt->bindValue(':claped', trim($claped), PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$result['total'];
}


}
