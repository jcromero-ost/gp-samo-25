<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Usuario, que se encarga de manejar operaciones sobre la tabla 'usuarios'
class Usuario {
    private $db; // Propiedad privada que almacenará la conexión a la base de datos

    // Constructor: se ejecuta al instanciar la clase
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los usuarios ordenados por nombre ascendentemente
    public function getAllUsuarios() {
        $stmt = $this->db->prepare("SELECT u.*, d.nombre AS nombre_departamento
                                        FROM usuarios u 
                                        JOIN departamentos d ON d.id = u.departamento_id
                                        ORDER BY nombre ASC"); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados como un array asociativo
    }

    // (Opcional) Obtener por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Método para crear un nuevo usuario
    public function create($data) {
        // Hashea la contraseña usando el algoritmo por defecto de PHP (actualmente bcrypt)
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            INSERT INTO usuarios 
                (nombre, email, password, alias, telefono, fecha_creacion, departamento_id, foto)
            VALUES 
                (:nombre, :email, :password, :alias, :telefono, :fecha_creacion, :departamento_id, :foto)
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hash); // Usa el hash en lugar de la contraseña sin cifrar
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
        $stmt->bindParam(':foto', $data['foto']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    public function comprobarEmail(string $email): bool {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    
    // Método para actualizar un usuario
    public function update($data) {    
        // Prepara la consulta SQL de inserción
        $sql = "UPDATE usuarios SET 
                    nombre = :nombre,
                    alias = :alias,
                    email = :email,
                    telefono = :telefono,
                    foto = :foto,
                    departamento_id = :departamento_id";

        if (!empty($data['foto'])) {
            $sql .= ", foto = :foto";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
        $stmt->bindParam(':foto', $data['foto']);
        $stmt->bindParam(':id', $data['id']);

        if (!empty($data['foto'])) {
            $stmt->bindParam(':foto', $data['foto']);
        }
    
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

    // Método para actualizar un usuario
    public function update_perfil($data) {    
        // Prepara la consulta SQL de inserción
        $sql = "UPDATE usuarios SET 
                    nombre = :nombre,
                    alias = :alias,
                    email = :email,
                    telefono = :telefono";

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':id', $data['id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    // Método para actualizar la foto de perfil de un usuario
    public function update_foto($data) {
        $sql = "UPDATE usuarios SET 
                    foto = :foto
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':foto', $data['foto']);
        $stmt->bindParam(':id', $data['id']);

        return $stmt->execute();
    }

    // Método para actualizar la contraseña de un usuario
    public function update_password($data) {
        $sql = "UPDATE usuarios SET 
                    password = :password
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':id', $data['id']);

        return $stmt->execute();
    }


}
