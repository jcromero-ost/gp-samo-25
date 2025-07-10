<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Escandalllo, que se encarga de manejar operaciones sobre la tabla 'colores'
class Colores {
    private $db; // Propiedad privada que almacenará la conexión a la base de datos

    // Constructor: se ejecuta al instanciar la clase
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los colores ordenados por nombre ascendentemente
    public function getAllColores() {
        $stmt = $this->db->prepare("SELECT * FROM cg_colores ORDER BY CLACOL ASC"); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados como un array asociativo
    }

    // Método para obtener todos los colores por código padre
    public function getAllColoresByCodigoPadre($codigo_padre) {
        $stmt = $this->db->prepare("SELECT * FROM cg_colores WHERE CLAART = :codigo_padre ORDER BY CLACOL ASC");
        $stmt->bindParam(':codigo_padre', $codigo_padre, PDO::PARAM_STR); // Usa STR si el código no es estrictamente numérico
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColoresConPaginacion($codigoPadre, $limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM cg_colores WHERE CLAART = :codigoPadre AND ACTIVO = 1 ORDER BY CLACOL ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':codigoPadre', $codigoPadre, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColoresConDatosDelArticulo($codigoPadre) {
        $stmt = $this->db->prepare("SELECT * FROM cg_colores WHERE CLAART = :codigoPadre AND ACTIVO = 1");
        $stmt->execute(['codigoPadre' => $codigoPadre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function countColores($codigoPadre) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cg_colores WHERE CLAART = :codigoPadre AND ACTIVO = 1");
        $stmt->execute(['codigoPadre' => $codigoPadre]);
        return (int)$stmt->fetchColumn();
    }
}
