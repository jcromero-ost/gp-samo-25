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
                header('Location:' . BASE_URL . '/usuarios_crear');
                exit;
            }

            // Verifica que todos los campos requeridos estén completos
            if (empty($nombre) || empty($email) || empty($password) || empty($alias) || empty($telefono) || empty($departamento_id)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location:' . BASE_URL . '/usuarios_crear');
                exit;
            }


            // Verificar si el email ya está registrado
            $usuario = new Usuario();
            if ($usuario->comprobarEmail($email)) {
                $_SESSION['error'] = 'El correo ya está registrado';
                header('Location: ' . BASE_URL . '/usuarios_crear');
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
            $nombre = $_POST['edit_nombre'] ?? '';
            $email = $_POST['edit_email'] ?? '';
            $alias = $_POST['edit_alias'] ?? '';
            $telefono = $_POST['edit_telefono'] ?? '';
            $departamento_id = $_POST['edit_departamento_id'] ?? '';
            $id = $_POST['id'];

            // Procesar imagen recortada en base64
            if (!empty($_POST['edit_foto_recortada'])) {
                $foto = $_POST['edit_foto_recortada']; // Base64 completa
            } else {
                $foto = 'default.jpeg'; // O podrías guardar null
            }

            // Actualiza un usuario usando el modelo Usuario
            $usuario = new Usuario();
            $usuario->update([
                'nombre' => $nombre,
                'email' => $email,
                'alias' => $alias,
                'telefono' => $telefono,
                'departamento_id' => $departamento_id,
                'foto' => $foto,
                'id' => $id
            ]);

            // Si el usuario editado es igual al logueado
            if ($_SESSION['id'] == $id) {
                $_SESSION['foto'] = $foto; // Guarda la imagen en la session
            }
            $_SESSION['success'] = 'Usuario actualizado correctamente.';
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/usuarios');
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

    // Método para actualizar tu perfil
    public function update_perfil() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $nombre = $_POST['edit_nombre'] ?? '';
            $alias = $_POST['edit_alias'] ?? '';
            $email = $_POST['edit_email'] ?? '';
            $telefono = $_POST['edit_telefono'] ?? '';
            $id = $_POST['id'];

            // Actualiza un usuario usando el modelo Usuario
            $usuario = new Usuario();
            $usuario->update_perfil([
                'nombre' => $nombre,
                'email' => $email,
                'alias' => $alias,
                'telefono' => $telefono,
                'id' => $id
            ]);

            $_SESSION['nombre'] = $nombre;
            $_SESSION['success'] = 'Perfil actualizado correctamente.';
            header('Location: ' . BASE_URL . '/perfil_editar');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/perfil_editar');
        exit;
    }

    // Método para actualizar la foto de perfil
    public function update_perfil_foto() {
        session_start(); // Inicia o reanuda la sesión

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $foto = $_POST['foto_base64'] ?? '';

            // Si no hay foto, se asigna una imagen por defecto
            if (empty($foto)) {
                $foto = 'default.jpeg';
            }

            // Actualiza la foto del usuario usando el modelo Usuario
            $usuario = new Usuario();
            $actualizado = $usuario->update_foto([
                'id' => $id,
                'foto' => $foto
            ]);

            if ($actualizado) {
                $_SESSION['foto'] = $foto;
                $_SESSION['success'] = 'Foto de perfil actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la foto de perfil.';
            }

            header('Location: ' . BASE_URL . '/perfil_editar');
            exit;
        }

        // Si no es POST, redirige a la página del perfil
        header('Location: ' . BASE_URL . '/perfil_editar');
        exit;
    }

    // Método para actualizar la contraseña del perfil
    public function update_password() {
        session_start(); // Inicia o reanuda la sesión

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $password = $_POST['new_password'] ?? '';
            $passwordConfirm = $_POST['new_password_confirm'] ?? '';

            // Validaciones básicas
            if (empty($id)) {
                $_SESSION['error'] = 'ID de usuario faltante.';
                header('Location: ' . BASE_URL . '/perfil_editar');
                exit;
            }

            if (empty($password) || empty($passwordConfirm)) {
                $_SESSION['error'] = 'Debe ingresar ambas contraseñas.';
                header('Location: ' . BASE_URL . '/perfil_editar');
                exit;
            }

            if ($password !== $passwordConfirm) {
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                header('Location: ' . BASE_URL . '/perfil_editar');
                exit;
            }

            // Hashea la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Actualiza la contraseña usando el modelo Usuario
            $usuario = new Usuario();
            $actualizado = $usuario->update_password([
                'id' => $id,
                'password' => $passwordHash
            ]);

            if ($actualizado) {
                $_SESSION['success'] = 'Contraseña actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la contraseña.';
            }

            header('Location: ' . BASE_URL . '/perfil_editar');
            exit;
        }

        // Si no es POST, redirige
        header('Location: ' . BASE_URL . '/perfil_editar');
        exit;
    }


}