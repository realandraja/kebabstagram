<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;


// Home page  route
$app->get('/','HomeController:index')->setName('home');

// A single Kebab page route
$app->get('/kebabs/{id}','KebabContentController:KebabById')->setName('single_kebab');

// A tag search page route
$app->get('/tags/{label}','KebabSearchController:tags')->setName('tag_kebab');

// A kebab search page route
$app->post('/search','KebabSearchController:index')->setName('search_kebab');


// Routes for guests
$app->group('', function(){

    // SignUp pages routes
    $this->get('/auth/signup','AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup','AuthController:postSignUp');

    // SingIn pages routes
    $this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin','AuthController:postSignIn');
    
})->add(new GuestMiddleware($container));


// Routes for authenficated users
$app->group('', function(){

    // Log Out page route
    $this->get('/auth/signout','AuthController:getSignOut')->setName('auth.signout');

    // Route for adding a new kebab
    $this->get('/new','KebabFormController:getKebabForm')->setName('new_kebab');
    $this->post('/new','KebabFormController:postKebabForm');

})->add(new AuthMiddleware($container));