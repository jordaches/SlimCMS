<?php

require '../vendor/autoload.php';

use Slim\App;

session_start();

//Start App
$app = new App([
    'settings'=> [
        'displayErrorDetails' => true,
    ]
]);

// constants
require __DIR__ . '/../src/config/bucket.php';
// Dependency injection
require __DIR__ . '/../src/config/dependencies.php';
// Middleware
require  __DIR__ . '/../src/config/services.php';
// Routes
require  __DIR__ . '/../src/routes/web.php'; //HTML
require __DIR__ . '/../src/routes/api.php'; //API

$app->run();