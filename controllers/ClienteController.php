<?php
// Incluye el modelo Cliente, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Cliente.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class ClienteController {

public function store()
{
    $campos = [
        ['name' => 'CLACLI', 'type' => 'I'],
        ['name' => 'CODIGO', 'type' => 'C', 'length' => 10],
        ['name' => 'NOMBRE', 'type' => 'C', 'length' => 50],
        ['name' => 'DIRECCION', 'type' => 'C', 'length' => 50],
        ['name' => 'LOCALIDAD', 'type' => 'C', 'length' => 30],
        ['name' => 'PROVINCIA', 'type' => 'C', 'length' => 30],
        ['name' => 'POSTAL', 'type' => 'B'],
        ['name' => 'PAIS', 'type' => 'C', 'length' => 30],
        ['name' => 'TELEFONO', 'type' => 'C', 'length' => 20],
        ['name' => 'FAX', 'type' => 'C', 'length' => 20],
        ['name' => 'EMAIL', 'type' => 'C', 'length' => 50],
        ['name' => 'CONTACTO', 'type' => 'C', 'length' => 50],
        ['name' => 'FALTA', 'type' => 'D'],
        ['name' => 'CIF', 'type' => 'C', 'length' => 15],
        ['name' => 'NOMBAN', 'type' => 'C', 'length' => 30],
        ['name' => 'DIRBAN', 'type' => 'C', 'length' => 50],
        ['name' => 'LOCBAN', 'type' => 'C', 'length' => 30],
        ['name' => 'PROVBAN', 'type' => 'C', 'length' => 30],
        ['name' => 'OFIBAN', 'type' => 'C', 'length' => 10],
        ['name' => 'ENTI', 'type' => 'C', 'length' => 10],
        ['name' => 'OFIC', 'type' => 'C', 'length' => 10],
        ['name' => 'NCTA', 'type' => 'C', 'length' => 20],
        ['name' => 'DC', 'type' => 'C', 'length' => 2],
        ['name' => 'PAGO1', 'type' => 'B'],
        ['name' => 'PAGO2', 'type' => 'B'],
        ['name' => 'PAGO3', 'type' => 'B'],
        ['name' => 'PAGO4', 'type' => 'B'],
        ['name' => 'DTOPP', 'type' => 'B'],
        ['name' => 'DTOCIAL', 'type' => 'B'],
        ['name' => 'NCTACON', 'type' => 'B'],
        ['name' => 'RIESGO', 'type' => 'B'],
        ['name' => 'REGIMENIVA', 'type' => 'B'],
        ['name' => 'DPAGO1', 'type' => 'B'],
        ['name' => 'DPAGO2', 'type' => 'B'],
        ['name' => 'CONTACT1', 'type' => 'C', 'length' => 50],
        ['name' => 'CARGO1', 'type' => 'C', 'length' => 30],
        ['name' => 'TELCONT1', 'type' => 'C', 'length' => 20],
        ['name' => 'CONTACT2', 'type' => 'C', 'length' => 50],
        ['name' => 'CARGO2', 'type' => 'C', 'length' => 30],
        ['name' => 'TELCONT2', 'type' => 'C', 'length' => 20],
        ['name' => 'CONTACT3', 'type' => 'C', 'length' => 50],
        ['name' => 'CARGO3', 'type' => 'C', 'length' => 30],
        ['name' => 'TELCONT3', 'type' => 'C', 'length' => 20],
        ['name' => 'CONTACT4', 'type' => 'C', 'length' => 50],
        ['name' => 'CARGO4', 'type' => 'C', 'length' => 30],
        ['name' => 'TELCONT4', 'type' => 'C', 'length' => 20],
        ['name' => 'NOTAS', 'type' => 'M'],
        ['name' => 'PORTES', 'type' => 'B'],
        ['name' => 'PORTESDESD', 'type' => 'B'],
        ['name' => 'AGRUPALB', 'type' => 'L'],
        ['name' => 'NOALB', 'type' => 'L'],
        ['name' => 'CLAAGE', 'type' => 'I'],
        ['name' => 'CLAFPA', 'type' => 'I'],
        ['name' => 'CLATIP', 'type' => 'I'],
        ['name' => 'CLATRA', 'type' => 'I'],
        ['name' => 'CLAZON', 'type' => 'I'],
        ['name' => 'CLATAR', 'type' => 'I'],
        ['name' => 'NOMBRECOM', 'type' => 'C', 'length' => 50],
        ['name' => 'NCODPROV', 'type' => 'C', 'length' => 10],
        ['name' => 'IBAN', 'type' => 'C', 'length' => 34],
        ['name' => 'GPSLAT', 'type' => 'B'],
        ['name' => 'GPSLON', 'type' => 'B'],
        ['name' => 'EMAILFE', 'type' => 'C', 'length' => 50],
        ['name' => 'CONSUMIDOR', 'type' => 'L'],
        ['name' => 'NOMUSER', 'type' => 'C', 'length' => 30],
        ['name' => 'PASSWORD', 'type' => 'C', 'length' => 30],
        ['name' => 'BIC', 'type' => 'C', 'length' => 11],
        ['name' => 'REFUNICTO', 'type' => 'C', 'length' => 20],
        ['name' => 'FECHACTO', 'type' => 'D'],
        ['name' => 'FIRSTRCBO', 'type' => 'L'],
        ['name' => 'ADMPUBLI', 'type' => 'L'],
        ['name' => 'CLAADMON', 'type' => 'I'],
        ['name' => 'NOVALESTPV', 'type' => 'L'],
        ['name' => 'ALTAONLINE', 'type' => 'L'],
        ['name' => 'BAJAONLINE', 'type' => 'L'],
        ['name' => 'MSG_ONLINE', 'type' => 'L'],
        ['name' => 'EMAILCONF', 'type' => 'L'],
        ['name' => 'NOMCNT', 'type' => 'C', 'length' => 30],
        ['name' => 'APELLCNT', 'type' => 'C', 'length' => 30],
        ['name' => 'RESETCOUNT', 'type' => 'B'],
        ['name' => 'RESETDATE', 'type' => 'T'],
        ['name' => 'CLAPAIS', 'type' => 'I'],
        ['name' => 'CLAUN', 'type' => 'I'],
        ['name' => 'NOPUBLI', 'type' => 'L'],
        ['name' => 'CODREGIVA', 'type' => 'C', 'length' => 10],
        ['name' => 'FTIPFACVEN', 'type' => 'L'],
        ['name' => 'TIPOCIF', 'type' => 'B'],
        ['name' => 'CODPAIS', 'type' => 'C', 'length' => 10],
        ['name' => 'TIPOCOBRO', 'type' => 'B'],
        ['name' => 'APLITARTPV', 'type' => 'L'],
        ['name' => 'BAJA', 'type' => 'L'],
        ['name' => 'WEB', 'type' => 'C', 'length' => 50],
        ['name' => 'OBSERVA', 'type' => 'C', 'length' => 100],
        ['name' => 'REGIMENIVB', 'type' => 'B'],
        ['name' => 'PARTIDA', 'type' => 'B'],
        ['name' => 'CLAFPAB', 'type' => 'B'],
        ['name' => 'PAGO1B', 'type' => 'B'],
        ['name' => 'PAGO2B', 'type' => 'B'],
        ['name' => 'PAGO3B', 'type' => 'B'],
        ['name' => 'PAGO4B', 'type' => 'B'],
        ['name' => 'DPAGO1B', 'type' => 'B'],
        ['name' => 'DPAGO2B', 'type' => 'B'],
        ['name' => 'CPOSTALEX', 'type' => 'C', 'length' => 10],
        ['name' => 'ENMAIL', 'type' => 'L']
    ];

    $datos = [];

    foreach ($campos as $campo) {
        $nombre = $campo['name'];
        $tipo = $campo['type'];
        $valor = $_POST[$nombre] ?? null;

        switch ($tipo) {
            case 'C':
                $length = $campo['length'] ?? 50;
                $valor = trim((string)$valor);
                $valor = mb_substr($valor, 0, $length, 'UTF-8');
                $datos[$nombre] = str_pad($valor, $length);
                break;

            case 'I': // Entero
                $datos[$nombre] = is_numeric($valor) ? intval($valor) : 0;
                break;

            case 'B':
                $datos[$nombre] = is_numeric($valor) ? floatval($valor) : 0.0;
                break;

            case 'D': // Fecha YYYYMMDD o 8 espacio
                if (!empty($valor) && strtotime($valor) !== false) {
                    $datos[$nombre] = date('Ymd', strtotime($valor));
                } else {
                    $datos[$nombre] = str_repeat(' ', 8);
                }
                break;

            case 'L': // Booleano
                $datos[$nombre] = ($valor === '1' || $valor === 'on' || strtolower($valor) === 'true') ? 'T' : 'F';
                break;

            case 'M': // Memo
                $datos[$nombre] = is_string($valor) ? $valor : '';
                break;

            case 'T':
                $datos[$nombre] = !empty($valor) && strtotime($valor) !== false ? strtotime($valor) : time();
                break;

            default:
                $datos[$nombre] = $valor ?? '';
        }
    }

    $cliente = new Cliente();
    $cliente->create($datos);

    header('Location: ' . BASE_URL . '/clientes');
    exit;
}

    /*
    // Método para crear un nuevo cliente (usualmente al enviar un formulario)
    public function store()
    {
        session_start(); // Inicia o reanuda la sesión

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario
            $data = $_POST;

            // Valida los campos obligatorios (ajusta según los campos requeridos)
            if (empty($data['nombre']) || empty($data['telefono'])) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: ' . BASE_URL . '/clientes_crear');
                exit;
            }

            // Intenta crear el cliente
            $clienteModel = new Cliente();
            $resultado = $clienteModel->create($data);

            if ($resultado === false) {
                $_SESSION['error'] = 'Error al crear el cliente.';
                // Puedes guardar más información del error para depuración
                $_SESSION['debug_post'] = $data;
                $_SESSION['debug_error'] = $clienteModel->reader->getLastError() ?? 'No hay error reportado por el DBF reader.';
                header('Location: ' . BASE_URL . '/clientes_crear');
                exit;
            }

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Cliente creado correctamente.';
            header('Location: ' . BASE_URL . '/clientes');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/clientes_crear');
        exit;
    }
    */


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
        session_start(); // Inicia o reanuda la sesión

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el CODIGO del cliente enviado por POST
            $codigo = $_POST['codigo'] ?? null;

            if ($codigo) {
                error_log("Intentando eliminar cliente con codigo: $codigo");

                // Instancia el modelo Cliente (asegúrate que esté incluido o autoload)
                $cliente = new Cliente();

                // Llama al método delete para eliminar el cliente
                $resultado = $cliente->delete($codigo);

                if ($resultado) {
                    error_log("Cliente eliminado correctamente.");
                    $_SESSION['success'] = 'Cliente eliminado correctamente.';
                } else {
                    error_log("Fallo al eliminar cliente con codigo: $codigo");
                    $_SESSION['error'] = 'No se pudo eliminar el cliente. Verifica el código.';
                }
            } else {
                error_log("No se proporcionó código para eliminar cliente.");
                $_SESSION['error'] = 'Código de cliente no proporcionado.';
            }

            // Redirige a la lista de clientes (evitar que se reenvíe el formulario)
            header('Location: ' . BASE_URL . '/clientes');
            exit;
        }

        // Si no es POST, redirige igualmente a la lista
        header('Location: ' . BASE_URL . '/clientes');
        exit;
    }


}