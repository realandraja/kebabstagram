<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = "users"; 

    protected $fillable = ['pseudo','nom','prenom','email','password'];

    protected $loginNames = ['pseudo'];

    /** Get the user photos*/
    public function photos()
    {
        return $this->hasMany("App\Models\Photos");
    }

    /** Add Signup credentials to database*/
    public function SignUp($credentials)
    {
        User::create($credentials);
    }

    /** Check user signin credentials*/
    public function SignIn($credentials)
    {
        if(array_key_exists('email', $credentials))
            $user = User::where(email,$credentials['email'])->first();
        else
            $user = User::where(pseudo,$credentials['pseudo'])->first();
        
        if( !$user || !password_verify($credentials['password'],$user->password) )
        {
            return false;
        }
        $_SESSION['user'] = $user->pseudo;

        return true;
    }

    /** Search for a user by username*/
    public function get_user($username)
    {
        return  User::where( 'pseudo' , '=', $username)->first();
    }


}