<?php

namespace App\Controllers\Auth;

use Respect\Validation\Validator as v;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;
use App\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuthController extends Controller
{

    /** Sign out the user */
    public function getSignOut($request,$response)
    {
        unset($_SESSION['user']);
         return $response->withRedirect($this->router->pathFor('home'));
    }

    /** Check if the user is logged in */
    public function check()
    {
        return isset($_SESSION['user']);
    }

    /** Return the current user details*/
    public function user()
    {
          $user = new \App\Models\User; 
          return $user->get_user($_SESSION['user']);
    }


    /** Display the log in form*/
    public function getSignIn($request,$response)
    { 
        $this->view->render($response,'auth/signin.twig');
    }

    /** Handles the log in form POST request*/
    public function postSignIn($request,$response)
    {
        $validation = $this->validator->validate($request,['login'=> v::email()]);

        if(!$validation->failed())
             $credentials['email'] = $request->getParam('login');
         else
             $credentials['pseudo'] = $request->getParam('login');

        $credentials['password'] = $request->getParam('password');
       
        $user = new \App\Models\User; 

        if(!$user->SignIn($credentials))
        {
            $this->flash->addMessage('error','Login ou mot de passe incorrect');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }

    /** Display the Sign up form*/
    public function getSignUp($request,$response)
    {
        return $this->view->render($response,'auth/signup.twig');
    }

    /** Handles the log out form POST request*/
    public function postSignUp($request,$response)
    {
        $validation = $this->validator->validate($request,[
            'email'    => v::email()->EmailAvailable(),
            'password' =>  v::notEmpty(),
            'pseudo' =>  v::alnum()->noWhitespace()->notEmpty()->UsernameAvailable(),
            'nom' => v::notEmpty()->alpha(),
            'prenom' => v::notEmpty()->alpha()
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $credentials = [
            'email'    => $request->getParam('email'),
            'password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
            'pseudo' => $request->getParam('pseudo'),
            'nom' => $request->getParam('nom'),
            'prenom' => $request->getParam('prenom')
        ];

        $user = new \App\Models\User; 
        $user->signup($credentials);
        $user->SignIn(['email'=> $request->getParam('email') , 'password'=> $request->getParam('password')  ]);

        $this->flash->addMessage('info','Votre inscription a bien été terminé !');
        return $response->withRedirect($this->router->pathFor('home')) ;
    }

    
}