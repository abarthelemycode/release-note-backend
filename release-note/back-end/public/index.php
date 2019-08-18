<?php
error_reporting(0);
ini_set("display_errors", E_ALL);
// Catch All fatal error and return json ( because slim doesn't catch fatal error )
register_shutdown_function('fatalErrorHandler');

function fatalErrorHandler()
{
    $error = error_get_last();

    if ($error !== null && $error['type'] === E_ERROR) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];

        $log = 'Error Fatal '.$errno.' : '.$errstr.' in '.$errfile.' on line '.$errline;
        error_log($log);
        // return json error
        header('Content-Type: application/json');
        header('Status: 500 Internal Server Error');
        echo json_encode(['error'=> true, 'message' => 'Internal Server Error']);
    }
}


if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// to remove
date_default_timezone_set('Europe/Paris');

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

// Run app
$app->run();

