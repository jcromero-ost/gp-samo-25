<?php

class Router
{
    // Arreglo donde se almacenan todas las rutas registradas
    private $routes = [];

    // Método para registrar rutas tipo GET
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    // Método para registrar rutas tipo POST
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    // Método privado que agrega una ruta al arreglo, con su método, path y callback
    private function addRoute($method, $path, $callback)
    {
        // compact('method', 'path', 'callback') crea un array asociativo con esas claves
        $this->routes[] = compact('method', 'path', 'callback');
    }

    // Método principal que se encarga de ejecutar la ruta correspondiente
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Ajustar la base (ej: /GPintranet)
        $base = '/GPintranet';
        if (str_starts_with($requestUri, $base)) {
            $requestUri = substr($requestUri, strlen($base));
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                $callback = $route['callback'];

                // Soporte para 'Clase@metodo'
                if (is_string($callback) && str_contains($callback, '@')) {
                    [$class, $method] = explode('@', $callback);

                    $controllerFile = __DIR__ . "/controllers/{$class}.php";
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;

                        if (class_exists($class)) {
                            $instance = new $class();

                            if (method_exists($instance, $method)) {
                                return call_user_func([$instance, $method], $_POST);
                            } else {
                                die("Método '$method' no encontrado en la clase '$class'");
                            }
                        } else {
                            die("Clase '$class' no encontrada.");
                        }
                    } else {
                        die("Archivo del controlador '$controllerFile' no existe.");
                    }
                }

                // Si es una función anónima
                if (is_callable($callback)) {
                    return call_user_func($callback);
                }

                die("Callback no válido.");
            }
        }

        http_response_code(404);
        echo "404 - Página no encontrada";
    }
}