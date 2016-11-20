<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';


use Respect\Validation\Validator as v;
use Cartalyst\Sentinel\Native\Facades\Sentinel;


// Add database
DB\Connection::bootEloquent("config.ini");



$app= new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true

            ],

]);
$container = $app->getContainer();


define('INC_ROOT', dirname(__DIR__));


$container["AuthController"] = function ($container){

  return new \App\Controllers\Auth\AuthController($container);
};

$container["Pagination"] = function ($container){
  
  return new \Xandros15\SlimPagination\Pagination($container);
};


$container["KebabFormController"] = function ($container){

  return new \App\Controllers\Kebab\KebabFormController($container);
};

$container["KebabContentController"] = function ($container){

  return new \App\Controllers\Kebab\KebabContentController($container);
};

$container["KebabSearchController"] = function ($container){

  return new \App\Controllers\Kebab\KebabSearchController($container);
};

$container["HomeController"] = function ($container){

  return new \App\Controllers\HomeController($container);
};


$container["flash"] = function ($container){

  return new \Slim\Flash\Messages;
};


$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(INC_ROOT . '/ressources/views', [
        'debug' => true,
        'cache' => false
    ]);
    
    $view->addExtension(new \Slim\Views\TwigExtension(

    	$container->router,
    	$container->request->getUri()
  	
  	));
    $view->addExtension(new Twig_Extension_Debug());

       $view->getEnvironment()->addGlobal('auth',[
        'check' => $container->AuthController->check(),
        'user' => $container->AuthController->user(),
      ]);

      $view->getEnvironment()->addGlobal('flash', $container->flash) ;
      //$view->getEnvironment()->addGlobal('', $container->flash) ;
      // var_dump($container->AuthController->user());
     //  var_dump($_SESSION);
    return $view;

};



/*
$container["AuthController"] = function ($container){

  return new \App\Controllers\Auth\AuthController($container);
};*/


$container["validator"] = function ($container){

  return new \App\Validation\Validator;
};
v::with('App\\Validation\\Rules\\');


$container['csrf'] = function($container) {
    return new \Slim\Csrf\Guard;
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);


require __DIR__ . '/../app/routes.php';