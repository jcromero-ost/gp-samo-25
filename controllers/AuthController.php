<?php
require_once 'models/auth.php'; // Incluye el modelo de autenticación.
require_once __DIR__ . '/../config/config.php'; // Carga la configuración, como BASE_URL u otras constantes.

class AuthController {

    public function login() {
        // Inicia la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Obtiene el valor del identificador (email o alias) y la contraseña del formulario
        $identifier = trim($_POST['email'] ?? ''); // Podrías renombrar el campo en el formulario a 'identifier' si quieres, pero si mantienes 'email' funciona igual
        $password = $_POST['passwd'] ?? '';

        // Verifica que ambos campos estén completos
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'Debes completar todos los campos';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Instancia el modelo de autenticación
        $auth = new Auth();
        // Intenta autenticar al usuario con email o alias
        $user = $auth->authenticate($identifier, $password);

        if ($user) {
            $this->iniciarSesion($user, false);
            header('Location: ' . BASE_URL . '/inicio');
        } else {
            $_SESSION['error'] = 'Datos incorrectos';
            header('Location: ' . BASE_URL . '/login');
        }

        exit;
    }


    public function logout() {
        // Inicia la sesión si no está iniciada y luego la destruye
        session_start();
        session_destroy(); // Elimina todos los datos de sesión
        $_SESSION['error'] = 'Sesión cerrada'; // Mensaje de sesion cerrada
        header('Location: ' . BASE_URL . '/login'); // Redirige al login
        exit;
    }

    private function iniciarSesion($user) {
        // Guarda información relevante del usuario en la sesión
        $_SESSION['user'] = $user;
        $_SESSION['id'] = $user['id']; // ID del usuario
        $_SESSION['nombre'] = $user['nombre']; // nombre del usuario
        $_SESSION['foto'] = $user['foto']; // nombre del usuario
        $_SESSION['success'] = 'Bienvenido, ' . htmlspecialchars($user['nombre'] ?? ''); // Mensaje de bienvenida

        //Cargar ejercicio predeterminado si existe
        if (!empty($user['ejercicio_predeterminado'])) {
            $_SESSION['ejercicio'] = (int) $user['ejercicio_predeterminado'];
        }

        session_write_close(); // Finaliza la escritura de sesión para liberar el bloqueo de sesión
    }
}
