<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Definición de la clase Articulo, que se encarga de manejar operaciones sobre la tabla 'articulos'
class Articulo {
    private $reader;

    public function __construct() {
        $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\articulo.dbf";
        $this->reader = new DBFReader($ruta);
    }

    public function getAllArticulos($offset = 0, $limit = 10) {
        $articulos = $this->reader->getRecords($offset, $limit);

        // Ordenar por 'FALTA' descendente si el campo existe (puede ser una fecha)
        usort($articulos, function ($a, $b) {
            return strcmp($b['CODIGO'], $a['CODIGO']);
        });

        return $articulos;
    }

    public function getTotal() {
        return $this->reader->getRecordCount();
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
