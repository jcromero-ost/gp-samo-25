<?php
// Incluye el modelo Usuario, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Usuario.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class UsuarioController {

    // Método para crear un nuevo usuario (usualmente al enviar un formulario)
    public function store() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $alias = $_POST['alias'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            date_default_timezone_set('Europe/Madrid');
            $fecha_creacion = date('Y-m-d');
            $departamento_id = $_POST['departamento_id'] ?? '';
            $foto = 'default.jpeg';

            // Validacion de contraseña
            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                header('Location: /crear_usuario');
                exit;
            }

            // Verifica que todos los campos requeridos estén completos
            if (empty($nombre) || empty($email) || empty($password) || empty($alias) || empty($telefono) || empty($departamento_id)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location:' . BASE_URL . '/usuarios_crear');
                exit;
            }

            // Procesar imagen recortada en base64
            if (!empty($_POST['foto_recortada'])) {
                $foto = $_POST['foto_recortada']; // Base64 completa
            } else {
                $foto = 'default.jpeg'; // O podrías guardar null
            }

            // Crea un nuevo usuario usando el modelo Usuario
            $usuario = new Usuario();
            $usuario->create([
                'nombre' => $nombre,
                'email' => $email,
                'password' => $password,
                'alias' => $alias,
                'telefono' => $telefono,
                'fecha_creacion' => $fecha_creacion,
                'departamento_id' => $departamento_id,
                'foto' => $foto
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Usuario creado correctamente.';
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/usuarios');
        exit;
    }

    // Método para actualizar un usuario
    public function update() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $alias = $_POST['alias'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $departamento_id = $_POST['departamento_id'] ?? '';
            $id = $_POST['id'];

            // Verifica que todos los campos requeridos estén completos
            if (empty($nombre) || empty($email) || empty($alias) || empty($telefono) || empty($departamento_id)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: /usuarios_crear');
                exit;
            }

            // Actualiza un usuario usando el modelo Usuario
            $usuario = new Usuario();
            $usuario->update([
                'nombre' => $nombre,
                'email' => $email,
                'alias' => $alias,
                'telefono' => $telefono,
                'departamento_id' => $departamento_id,
                'id' => $id
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Usuario actualizado correctamente.';
            header('Location: ' . BASE_URL . '/usuarios_crear');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/usuarios_crear');
        exit;
    }

    // Método para borrar un usuario
    public function delete() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $id = $_POST['id'];

            // Borra un usuario usando el modelo Usuario
            $usuario = new Usuario();
            $usuario->delete([
                'id' => $id
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Usuario eliminado correctamente.';
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/usuarios');
        exit;
    }
}