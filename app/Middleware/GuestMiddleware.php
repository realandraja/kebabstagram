<?php

namespace App\Middleware;

class GuestMiddleware extends Middleware
{

    public function __invoke($request,$response,$next)
    {
        if( $this->container->AuthController->check())
        {
            //$this->container->flash->addMessage('error','Vous devez vous connecer pour acccèder à cette page ');
            return $response->withRedirect($this->container->router->pathFor('home'));
        }
        $response = $next($request,$response);
        return $response;
    }
}