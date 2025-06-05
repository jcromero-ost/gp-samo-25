<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase departamento, que se encarga de manejar operaciones sobre la tabla 'departamentos'
class Departamento {
    private $db; // Propiedad privada que almacenará la conexión a la base de datos

    // Constructor: se ejecuta al instanciar la clase
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los departamentos ordenados por nombre ascendentemente
    public function getAllDepartamentos() {
        $stmt = $this->db->prepare("SELECT * FROM departamentos ORDER BY nombre ASC"); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados como un array asociativo
    }

    // Método para crear un nuevo departamento
    public function create($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            INSERT INTO departamentos 
                (nombre)
            VALUES 
                (:nombre)
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    
    // Método para actualizar un departamento
    public function update($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            UPDATE departamentos 
                SET nombre = :nombre
            WHERE id= :id
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':id', $data['id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    // Método para borrar un departamento
    public function delete($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("DELETE FROM departamentos WHERE id= :id");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':id', $data['id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }
    
    // Método para obtener un departamento por su ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM departamentos WHERE id = :id"); // Prepara la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Asocia el parámetro :id con el valor $id
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un único resultado como array asociativo
    }    
}
