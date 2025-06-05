<?php
require_once 'models/auth.php'; // Incluye el modelo de autenticación.
require_once __DIR__ . '/../config/config.php'; // Carga la configuración, como BASE_URL u otras constantes.

class AuthController {

    public function login() {
        // Inicia la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Obtiene los valores de email y password del formulario, con limpieza básica
        $email = trim($_POST['email'] ?? ''); // Elimina espacios en blanco al inicio y final
        $password = $_POST['passwd'] ?? ''; // Recupera la contraseña

        // Verifica que ambos campos estén completos
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Debes completar todos los campos.';
            header('Location: ' . BASE_URL . '/login'); // Redirige al login si faltan datos
            exit;
        }

        // Instancia el modelo de autenticación
        $auth = new Auth();
        // Intenta autenticar al usuario con los datos proporcionados
        $user = $auth->authenticate($email, $password);

        // Si el usuario existe y es válido
        if ($user) {
            $this->iniciarSesion($user, false); // Inicia sesión guardando los datos en $_SESSION
            header('Location: ' . BASE_URL . '/usuarios_crear'); // Redirige al login
        } else {
            // Si falla la autenticación, muestra un mensaje de error
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: ' . BASE_URL . '/login'); // Redirige al login
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
        $_SESSION['success'] = 'Bienvenido, ' . htmlspecialchars($user['nombre'] ?? ''); // Mensaje de bienvenida
        session_write_close(); // Finaliza la escritura de sesión para liberar el bloqueo de sesión
    }
}
