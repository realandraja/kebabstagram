<?php

namespace App\Controllers\Kebab;

use Respect\Validation\Validator as v;
use App\Controllers\Controller;
use App\Uploads\Uploads;
use App\Models\{Photos, Tags};
use Illuminate\{Database\Eloquent\Model, Pagination\Paginator};

class KebabSearchController extends Controller
{
    
    public function tags($request,$response,$args)
    {   

        $label = $args['label'] ;
        
        // Getting base and upload folder paths
        $base_path = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getBasePath();
        $tag_path = $base_path . "/tags/" . $label;
        $uploaddir =  $base_path . "/uploads/";

        // Setting up pagination
        $this->pagination($request,$response);

        // Getting the list of Photos matching the given tag
        $photo =  new \App\Models\Photos;
        $list_kebabs = $photo->get_photos_by_tags($label)[0];
        $kk = $photo->get_photos_by_tags($label)[1];

        // Passing data to the view
        $data = [ 'uploaddir' => $uploaddir , 'kb' => $list_kebabs , 'base_path' => $base_path, 'kk' => $kk , 'label' => $label  ];
        return $this->view->render( $response, 'kebab/search_kebab.twig', $data );
    }

    public function index($request,$response,$args)
    {
        $keyword = $request->getParam('search') ;
        $photo = new \App\Models\Photos;

        // Getting base and upload folder paths
        $base_path = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getBasePath();
        $uploaddir =  $base_path . "/uploads/";

        // Setting up pagination
        $this->pagination($request,$response);

        $search_type = $request->getParam('search_type') ;
        
        // if search by tags
        if ( $search_type == 'tag' )  
        {
           $tag = $request->getParam('search') ;
           return $response->withRedirect( $this->router->pathFor('tag_kebab',['label' => $tag ] ) );
        }

        // if search by username
        else if ( $search_type == 'username' )  
        {
            $list_kebabs = $photo->photos_by_username($keyword) ;
            $data = [ 'uploaddir' => $uploaddir , 'kb' => $list_kebabs , 'base_path' => $base_path, 'kk' => $kk ];
            return $this->view->render( $response, 'kebab/search_kebab.twig', $data );
        }
        else
        {
            $list_pics = $photo->photos_by_keyword($keyword) ;
            $data = [ 'uploaddir' => $uploaddir , 'kb' => $list_pics , 'base_path' => $base_path, 'kk' => $kk ];
            return $this->view->render( $response, 'kebab/search_kebab.twig', $data );
        }
    }

    /** Setting up validation */
    private function pagination($request,$response)
    {
        $current_page = $request->getParam('page');
        Paginator::currentPageResolver(function() use ($current_page) {
             return $current_page;
        });
    }

}