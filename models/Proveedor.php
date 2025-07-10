<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Proveedor, que se encarga de manejar operaciones sobre la tabla 'proveedores'
class Proveedor {
    private $db;
    private $ruta;

    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los proveedores ordenados por nombre ascendentemente
    public function getAllProveedores($offset = 0, $limit = 15) {
        $stmt = $this->db->prepare("SELECT * FROM cg_proveedores ORDER BY CLAPRO ASC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cg_proveedores");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    public function getProveedoresFiltrados($filtros, $offset = 0, $limit = 15) {
        $sql = "SELECT * FROM cg_proveedores WHERE 1=1";
        $params = [];

        if (!empty($filtros['codigo'])) {
            $sql .= " AND CODIGO = :codigo";
            $params[':codigo'] = $filtros['codigo'];
        }

        if (!empty($filtros['nombre'])) {
            $sql .= " AND NOMBRE LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        if (!empty($filtros['telefono'])) {
            $sql .= " AND TELEFONO LIKE :telefono";
            $params[':telefono'] = '%' . $filtros['telefono'] . '%';
        }

        $sql .= " ORDER BY CLAPRO ASC LIMIT :offset, :limit";

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
        $sql = "SELECT COUNT(*) as total FROM cg_proveedores WHERE 1=1";
        $params = [];

        if (!empty($filtros['codigo'])) {
            $sql .= " AND CODIGO LIKE :codigo";
            $params[':codigo'] = '%' . $filtros['codigo'] . '%';
        }

        if (!empty($filtros['nombre'])) {
            $sql .= " AND NOMBRE LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        if (!empty($filtros['telefono'])) {
            $sql .= " AND TELEFONO LIKE :telefono";
            $params[':telefono'] = '%' . $filtros['telefono'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
}
