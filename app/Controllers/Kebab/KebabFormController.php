<?php

namespace App\Controllers\Kebab;

use Respect\Validation\Validator as v;
use App\Controllers\Controller;
use App\Uploads\Uploads;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class KebabFormController extends Controller
{

    /** Add Kebab form */
    public function getKebabForm($request,$response)
    {
         return $this->view->render($response,'kebab/add_kebab.twig');
    }


    /** Handles POST for add kebab form */
    public function postKebabForm($request,$response)
    {
        $uploaddir =  dirname( __DIR__ , 3 ) . "/public/uploads/" ;
        $temp_uploaded_file =  $_FILES['file']['tmp_name'] ;
        
        // Validate the form inputs
        $validation = $this->validator->validate($request,[
            'titre'    => v::notEmpty()->length(1, 60),
            'description' =>  v::length(null, 1000),
            'tags' => v::arrayVal()->each( v::oneOf( v::alnum(), v::not(v::notEmpty()) ) ), 
            'endroit'=> v::notEmpty()->length(1, 255), 
        ],[
            $temp_uploaded_file =>  v::image()->size('1KB', '5MB')->uploaded()
        ]);

        // Generate a random name for the file
        $file_name = time().uniqid(rand()) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) ;
        $dir_uploaded_file = $uploaddir . $file_name ;

        // Check if the validation is correct and if the file is uploaded
        if( $validation->failed() || !move_uploaded_file( $temp_uploaded_file , $dir_uploaded_file) ){
            return $response->withRedirect($this->router->pathFor('new_kebab'));
        }

        // Get the kebab credentials
        $photo =  new \App\Models\Photos; 
        $credentials_photos = [
            'titre'    => $request->getParam('titre'),
            'description' => $request->getParam('description'),
            'endroit' => $request->getParam('endroit'),
            'user_id' => $this->container->AuthController->user()['id'],
            'nom_image' => $file_name,
        ];

        // Add tags to an array
        $j = 0 ;
        foreach( $request->getParam('tags') as $tag)
        {
            $tag = trim($tag);
            
            if(!empty($tag))
            {
                $credentials_tags[$j] = $tag ;
                $j++;
            }

        }

        // Create a kebab and redirect the user to home page
        $photo->create_kebab( $credentials_photos,$credentials_tags ) ;
        $this->flash->addMessage('info','Le kebab a bien été ajouté !');
        return $response->withRedirect($this->router->pathFor('home')) ;
    }



}