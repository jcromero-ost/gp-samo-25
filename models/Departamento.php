<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Usuario, que se encarga de manejar operaciones sobre la tabla 'departamentos'
class Departamento {
    private $db; // Propiedad privada que almacenará la conexión a la base de datos

    // Constructor: se ejecuta al instanciar la clase
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los usuarios ordenados por nombre ascendentemente
    public function getAllDepartamentos() {
        $stmt = $this->db->prepare("SELECT * FROM departamentos ORDER BY nombre ASC"); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados como un array asociativo
    }

    // Método para crear un nuevo usuario
    public function create($data) {
        // Hashea la contraseña usando el algoritmo por defecto de PHP (actualmente bcrypt)
        $hash = password_hash($data['passwd'], PASSWORD_DEFAULT);
    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            INSERT INTO usuarios 
                (nombre, email, passwd, alias, telefono, fecha_creacion, departamento_id)
            VALUES 
                (:nombre, :email, :passwd, :alias, :telefono, :fecha_creacion, :departamento_id)
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':passwd', $hash); // Usa el hash en lugar de la contraseña sin cifrar
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    
    // Método para actualizar un usuario
    public function update($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            UPDATE usuarios 
                SET nombre = :nombre, email= :email, alias= :alias, telefono= :telefono, departamento_id= :departamento_id
            WHERE id= :id
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
        $stmt->bindParam(':id', $data['id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    // Método para borrar un usuario
    public function delete($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id= :id");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':id', $data['id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }
    
    // Método opcional para obtener un usuario por su ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id"); // Prepara la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Asocia el parámetro :id con el valor $id
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un único resultado como array asociativo
    }    
}
