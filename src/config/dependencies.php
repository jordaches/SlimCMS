<?php

use Delight\Auth\Auth;
use Delight\Db\PdoDsn;
use Slim\Http\Environment;

$container = $app->getContainer();

//Adding a view to the container
$container['view'] = function ($container) {

    $view = new \Slim\Views\Twig(__DIR__ . VIEWS_DIR, [
        'cache' => false
    ]);
    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

//Slim fix
$container['environment'] = function () {
    // Fix the Slim 3 subdirectory issue (#1529)
    // This fix makes it possible to run the app from localhost/slim3-app
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['REAL_SCRIPT_NAME'] = $scriptName;
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '\\' . basename($scriptName);
    return new Environment($_SERVER);
};

//Adding DB to container
$container['db'] = function (){
    return new Medoo\Medoo([
        'database_type' => 'mysql',
        'database_name' => DB_NAME,
        'server' => DB_HOST,
        'username' => DB_USER,
        'password' => DB_PASSWORD,
    ]);
};

//Adding Authentication to container.
$container['auth'] = function(){
    $db = new PdoDsn('mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8mb4',
        DB_USER,
        DB_PASSWORD);
    return new Auth($db);
};

//Setting Upload Directory Path in container
$container['upload_directory'] = __DIR__ . UPLOAD_DIR;

// Add Flash Message Support to Container.
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
