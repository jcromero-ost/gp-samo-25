<?php
// Incluye el archivo de conexión a la base de datos (presumiblemente contiene la clase DBFReader)
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Articulo, que se encarga de manejar operaciones sobre la tabla 'articulos'
class Articulo {
    private $db; // Propiedad para manejar el lector de archivos DBF

    // Constructor: inicializa el lector DBF apuntando al archivo 'articulo.dbf'
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los articulos ordenados por nombre ascendentemente
    public function getAllArticulos($offset = 0, $limit = 15) {
        $stmt = $this->db->prepare("SELECT * FROM cg_articulos ORDER BY CLAART ASC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para buscar artículos por código o nombre (búsqueda insensible a mayúsculas)
    public function buscarPorCodigoONombre($busqueda) {
        $busqueda = '%' . mb_strtolower($busqueda) . '%';

        $stmt = $this->db->prepare("
            SELECT * FROM cg_articulos 
            WHERE LOWER(CODIGO) LIKE :busqueda OR LOWER(NOMBRE) LIKE :busqueda 
            ORDER BY CLAART ASC
            LIMIT 50
        ");
        $stmt->bindValue(':busqueda', $busqueda, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Devuelve solo los artículos cuyos códigos estén en el array proporcionado
    public function getArticulosPorCodigos(array $codigos) {
        if (empty($codigos)) return [];

        // Crea placeholders seguros para PDO
        $placeholders = implode(',', array_fill(0, count($codigos), '?'));

        $stmt = $this->db->prepare("SELECT * FROM cg_articulos WHERE CODIGO IN ($placeholders)");
        foreach ($codigos as $i => $codigo) {
            $stmt->bindValue($i + 1, $codigo, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve el total de registros en el archivo DBF
    public function getTotal() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cg_articulos");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    public function getArticulosFiltrados($filtros, $offset = 0, $limit = 15) {
        $sql = "SELECT * FROM cg_articulos WHERE 1=1";
        $params = [];

        if (!empty($filtros['codigo'])) {
            $sql .= " AND CODIGO = :codigo";
            $params[':codigo'] = $filtros['codigo'];
        }

        if (!empty($filtros['nombre'])) {
            $sql .= " AND NOMBRE LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        $sql .= " ORDER BY CLAART ASC LIMIT :offset, :limit";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalFiltrado($filtros) {
        $sql = "SELECT COUNT(*) as total FROM cg_articulos WHERE 1=1";
        $params = [];

        if (!empty($filtros['codigo'])) {
            $sql .= " AND CODIGO LIKE :codigo";
            $params[':codigo'] = '%' . $filtros['codigo'] . '%';
        }

        if (!empty($filtros['nombre'])) {
            $sql .= " AND NOMBRE LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // Método para obtener todos los articulos sin orden de fabricacion
public function countArticulosSinOrden($claart) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM cg_pedidos_lineas WHERE COMENT = '' AND CLAART = :claart");
    $stmt->bindValue(':claart', $claart);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

}
