<?php


namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\{Photos, Tags};
use Slim\Views\Twig as View;
use Illuminate\Pagination\Paginator;


class HomeController extends Controller
{

    /** Home page controller */
	public function index($request,$response,$args)
    {

        // Getting base and upload folder paths
        $base_path = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getBasePath();
        $uploaddir =  $base_path . "/uploads/";

        // Setting up pagination
        $current_page = $request->getParam('page');
        Paginator::currentPageResolver(function() use ($current_page) {
             return $current_page;
        });

        // Getting the list of kebabs from Photos model
        $photo =  new \App\Models\Photos;
        $list_kebabs = $photo->get_all_kebabs();
        
        // Passing data to home view
        $data = ['uploaddir' => $uploaddir , 'kb' => $list_kebabs , 'base_path' => $base_path ];
        return $this->view->render( $response, 'home.twig', $data );
    }

}