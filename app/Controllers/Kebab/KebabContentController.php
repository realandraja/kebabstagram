<?php

namespace App\Controllers\Kebab;

use Respect\Validation\Validator as v;
use App\Models\{Photos, Tags};
use App\Controllers\Controller;
use App\Uploads\Uploads;
use Illuminate\Database\Eloquent\Model;

class KebabContentController extends Controller
{

   public function KebabById($request,$response,$args)
    {
        // Getting base and upload folder paths
        $base_path = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getBasePath();
        $uploaddir =  $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getBasePath() . "/uploads/";
        // Check if kebab exists in database
        $id = $args['id'] ; 
        $photo =  new \App\Models\Photos;
        if( $photo->get_photo($id) === null)
        {
            return $response->withRedirect($this->router->pathFor('home')) ;
        }   
        $data = ['kebab' => $photo->get_photo($id) , 'uploaddir' => $uploaddir , 'base_path' => $base_path];
        
        return $this->view->render( $response, 'kebab/single_kebab.twig', $data );
    }



}