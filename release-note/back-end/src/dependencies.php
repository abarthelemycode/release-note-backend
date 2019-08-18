<?php

use Slim\App;
use App\Services\FeatureService;
use App\Services\TagService;
use App\Services\CategoryService;
use App\Services\UserService;
use App\Services\TokenService;
use App\Services\EmailService;
use App\Middlewares\ErrorMiddleware;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['viewRenderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings =  $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    $container['login'] = function ($c) {
        $settings = $container['settings']['login'];
        return $settings;
    };


    // services
    $container['featureService'] = function ($c) {
        $service = new FeatureService();
        return $service;
    };
    
    $container['userService'] = function ($c) {
        $service = new UserService();
        return $service;
    };

    $container['tagService'] = function ($c) {
        $service = new TagService();
        return $service;
    };

    $container['categoryService'] = function ($c) {
        $service = new CategoryService();
        return $service;
    };

    $container['tokenService'] = function ($c) {
        $service = new TokenService();
        return $service;
    };

    $container['emailService'] = function ($c) {
        $settings = $c['settings']['smtp'];
        $service = new EmailService($settings);
        return $service;
    };

    // error handlers
    $container['notFoundHandler'] = function ($c) {
        return new ErrorMiddleware($c, 404);
    };

    $container['errorHandler'] = function ($c) {
        return new ErrorMiddleware($c, 500);
    };

    // doesnt work on php 5.6.4 only on php 7+
    $container['phpErrorHandler'] = function ($c) {
        return new ErrorMiddleware($c, 500);
    };

    // Iluminate ORM integration
    
    // doesnt work because of versionning of Eloquent (for php5.6.4 and illuminate\database 5.4.36 )
    // $container['db'] = function ($c) {
    //     $capsule = new \Illuminate\Database\Capsule\Manager;
    //     $capsule->addConnection($container['settings']['db']);
    //     $capsule->setAsGlobal();
    //     $capsule->bootEloquent();
    //     return $capsule;
    // };

    // work on php5.6.4 and illuminate\database 5.4.36
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $container['db'] = function ($container) use ($capsule) {
        $capsule::connection()->enableQueryLog();
        return $capsule;
    };
};
