<?php
return [
    'settings' => [
        'displayErrorDetails'       => true, // set to false in production
        'addContentLengthHeader'    => false, // Allow the web server to send the content-length header
        'base_url'                  => 'http://localhost:8080/',
        'img_url'                   => 'http://localhost:8080/sources/',

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        // key for jwt
        'login' => [
            'crypt_salt'    => '$OFHwODZB$my4UbcqrUkmU_$',
            'secretkey'     => 'mysecretkeydelamortquitue',
        ],
        // smtp settings
        'smtp' => [
            'host'         => 'smtp.gmail.com',
            'username'     => 'usergmail@gmail.com',
            'password'     => 'passwordgmail',
            'secure'       => 'tls',
            'port'         => 587,
        ],
        //database settings
        "db" => [
            'driver' => 'sqlite',
            'host' => 'localhost',
            'database' => __DIR__ . '/../db/release-note.db',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],
];
