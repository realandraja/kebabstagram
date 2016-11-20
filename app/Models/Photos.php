<?php

namespace App\Models;


use App\Models\{Tags, User as User};
use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
	protected $table = "photos"; 

    protected $fillable = ['titre','endroit','description','nom_image','user_id','nom_image'];

    /** Get Kebab tags*/
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tags');
    }

    /** Defin relationship between a user and a kebab*/
    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }

    /** Get a kebab y it's ID */
    public function get_photo($id)
    {
        return $this->findOrFail($id);
    }

    /** Creates a kebab*/
    public function create_kebab($credentials_photo,$credentials_tags)
    {

        $id_photo = Photos::create($credentials_photo)->id;
        $photo = Photos::find($id_photo);
        
        if(!empty($credentials_tags))
        {
            foreach($credentials_tags as $cr_tag)
            {
                $tag =  new \App\Models\Tags(['label' => $cr_tag ]) ;
                $photo->tags()->save($tag);
            }
        }
 
    }

    /** Get list of all kebabs **/
    function get_all_kebabs()
    {
        return Photos::latest('updated_at')->simplePaginate(3);
    }

    /** Get kebabs by tags*/
    public function get_photos_by_tags($label)
    {
        $array_photos = array();
        $tags = Tags::where('label', $label)->simplePaginate(3);
        foreach( $tags as $tag )
        {
            foreach( $tag->photos()->get() as $photo )
                array_push($array_photos, $photo);
        }
        return array($array_photos,$tags) ;
   }

   /** Get kebabs by username*/
   public function photos_by_username($username)
   {
        $user = User::where('pseudo',$username)->first();
        if(is_null($user))
            return null;
        else
            return $user->photos()->get();
   }

   /** Get kebabs by keyword*/
   public function photos_by_keyword($search)
   {    
        return Photos::where('titre', 'like', "%$search%")->orWhere('description', 'like', "%$search%")->get();
   }

}