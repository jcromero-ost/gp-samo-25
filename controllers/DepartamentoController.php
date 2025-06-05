<?php
// Incluye el modelo Departamento, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Departamento.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class DepartamentoController {

    // Método para crear un nuevo departamento (usualmente al enviar un formulario)
    public function store() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $nombre = $_POST['nombre'] ?? '';

            // Verifica que todos los campos requeridos estén completos
            if (empty($nombre)) {
                $_SESSION['error'] = 'Debes ingresar un nombre para crear un departamento';
                header('Location:' . BASE_URL . '/departamentos');
                exit;
            }

            // Crea un nuevo departamento usando el modelo Departamento
            $departamento = new Departamento();
            $departamento->create([
                'nombre' => $nombre
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Departamento creado correctamente.';
            header('Location: ' . BASE_URL . '/departamentos');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/departamentos');
        exit;
    }

    // Método para actualizar un departamento
    public function update() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $nombre = $_POST['nombre'] ?? '';
            $id = $_SESSION['id'];

            // Verifica que todos los campos requeridos estén completos
            if (empty($nombre)) {
                $_SESSION['error'] = 'Debes ingresar un nombre para crear un departamento';
                header('Location:' . BASE_URL . '/departamentos');
                exit;
            }

            // Actualiza un departamento usando el modelo Departamento
            $departamento = new Departamento();
            $departamento->update([
                'nombre' => $nombre,
                'id' => $id
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Departamento actualizado correctamente.';
            header('Location: ' . BASE_URL . '/departamentos');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/departamentos');
        exit;
    }

    // Método para borrar un departamento
    public function delete() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $id = $_SESSION['id'];

            // Borra un departamento usando el modelo Departamento
            $departamento = new Departamento();
            $departamento->delete([
                'id' => $id
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Departamento eliminado correctamente.';
            header('Location: ' . BASE_URL . '/departamentos');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/departamentos');
        exit;
    }
}

// Controlador centralizado según la acción enviada por POST
if (isset($_POST['accion'])) {
    $controller = new DepartamentoController();

    switch ($_POST['accion']) {
        case 'editar':
            $controller->update();
            break;
        case 'eliminar':
            $controller->delete();
            break;
    }
}
