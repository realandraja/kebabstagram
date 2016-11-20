<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = "tags"; 

    protected $fillable = ['label'];

    public $timestamps = false;

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photos');
    }
   
   
}