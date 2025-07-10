<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Ejercicio, que se encarga de manejar operaciones sobre la tabla 'ejercicios'
class Ejercicio {
    private $db; // Propiedad para manejar el lector de archivos DBF

    // Constructor: inicializa el lector DBF apuntando al archivo 'articulo.dbf'
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los ejercicios ordenados por nombre ascendentemente
    public function getAllEjercicios() {
        $stmt = $this->db->prepare("SELECT * FROM cg_ejercicios ORDER BY CLAEJE ASC"); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados como un array asociativo
    }

    public function getEjerciciosFiltrados($filtros, $offset = 0, $limit = 15) {
        $sql = "SELECT * FROM cg_ejercicios WHERE 1=1";
        $params = [];

        if (!empty($filtros['codigo'])) {
            $sql .= " AND CLAEJE = :codigo";
            $params[':codigo'] = $filtros['codigo'];
        }

        if (!empty($filtros['nombre'])) {
            $sql .= " AND NOMEJE LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        $sql .= " ORDER BY CLAEJE ASC LIMIT :offset, :limit";

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
        $sql = "SELECT COUNT(*) as total FROM cg_ejercicios WHERE 1=1";
        $params = [];

        if (!empty($filtros['codigo'])) {
            $sql .= " AND CLAEJE LIKE :codigo";
            $params[':codigo'] = '%' . $filtros['codigo'] . '%';
        }

        if (!empty($filtros['nombre'])) {
            $sql .= " AND NOMEJE LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        if (!empty($filtros['desde'])) {
            $sql .= " AND DESDE >= :desde";
            $params[':desde'] = $filtros['desde'];
        }

        if (!empty($filtros['hasta'])) {
            $sql .= " AND HASTA <= :hasta";
            $params[':hasta'] = $filtros['hasta'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // Método para obtener todos los articulos ordenados por nombre ascendentemente
    public function getAllEjerciciosPaginados($offset = 0, $limit = 15) {
        $stmt = $this->db->prepare("SELECT * FROM cg_ejercicios ORDER BY CLAEJE ASC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve el total de registros en el archivo DBF
    public function getTotal() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cg_ejercicios");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
}
