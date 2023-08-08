<?php
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors_errors', 1);
error_reporting(E_ALL);
function custom_autoload($class_name)
{
    $directories = array(
        "/src/Database/",
        "/src/ErrorHandler/",
        "/src/Product/",
        "/src/User/",
    );

    foreach ($directories as $directory) {
        $file = __DIR__ . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once($file);
            return;
        }
    }
}
// require_once("../vendor/autoload.php");
spl_autoload_register('custom_autoload');

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$parts = explode("/api", $_SERVER["REQUEST_URI"]);


$id = $parts[2] ?? null;

$database = new Database("localhost", "trainer", "root", "");

switch ($parts[1]) {
    case '/products':
        $gateway = new ProductGateway($database);

        $controller = new ProductController($gateway);

        break;

    case '/users':
        $gateway = new UserGateway($database);

        $controller = new UserController($gateway);

        break;

    case '/auth':

        $gateway = new UserGateway($database);

        $controller = new UserController($gateway);

        break;

}

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);