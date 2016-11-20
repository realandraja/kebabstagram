<?php
namespace DB;

use Illuminate\Database\Capsule\Manager ;

class Connection {

        /** Boot eloquent*/
        public static  function bootEloquent($file) {

            $conf = parse_ini_file($file);
            
            //create a new instance of Manager
            $db= new Manager();
            $db->addConnection($conf);

            //make this instance available globally
            $db->setAsGlobal();

            //set up the ORM Eloquent
            $db->bootEloquent();
        }

}
