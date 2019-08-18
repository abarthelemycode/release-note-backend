<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\FeatureController;
use App\Controllers\ResourceController;
use App\Controllers\AuthenticationController;
use App\Controllers\ErrorController;
use App\Controllers\EmailController;
use App\Middlewares\LoginMiddleware;

return function (App $app) {
    $container = $app->getContainer();

    // $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
    //     // Sample log message
    //     $container->get('logger')->info("Slim-Skeleton '/' route");

    //     // Render index view
    //     return $container->get('renderer')->render($response, 'index.phtml', $args);
    // });

    $app->group('/api', function(\Slim\App $app) use ($container) {
    
        $app->post('/login', AuthenticationController::class.':login');

         // resources routes
        $app->get('/tags', ResourceController::class.':getTags');
        $app->get('/categories', ResourceController::class.':getCategories');
        
        // all following routes need login authentication

        // send email routes
        $app->post('/send-releasenote',  EmailController::class.':sendReleaseNote')->add(new LoginMiddleware($container));

        // features
        $app->get('/features',  FeatureController::class.':getAll')->add(new LoginMiddleware($container));
        $app->get('/features/{id}',  FeatureController::class.':getOne')->add(new LoginMiddleware($container));
        $app->post('/features',  FeatureController::class.':add')->add(new LoginMiddleware($container));
        $app->put('/features/{id}',  FeatureController::class.':update')->add(new LoginMiddleware($container));
        $app->delete('/features/{id}',  FeatureController::class.':delete')->add(new LoginMiddleware($container));
    });

    // Catch-all route to serve a 404 Not Found page if none of the routes match
    // NOTE: make sure this route is defined last
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($request, $response) {
        $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
        return $handler($request, $response);
    });
};
