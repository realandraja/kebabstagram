<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\AllOfException;
class Validator
{
    protected $errors;

    public function validate($request, array $posted_rules , array $file_rules = [] )
    {
        foreach($posted_rules as $field => $rule){
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (AllOfException $e) {
                var_dump($e->getMessages());
                $this->errors[$field] = $e->getMessages();
            }
        }

        foreach($file_rules as $content => $rule){
            try {
                $rule->setName('fichier')->assert($content);
            } catch (AllOfException $e) {
                 var_dump($e->getMessages());
                $this->errors['fichier'] = $e->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;
        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
    public function validate_f(array $rules)
    {
        foreach($rules as $field => $rule){
            try {
                $rule->setName(ucfirst($field))->assert( $field );
            } catch (AllOfException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;
        return $this;
    }

    public function test($usernameValidator)
    {
            try {
                $usernameValidator->setName('Fichier')->assert('really messed up screen#name');
            } 
            catch(AllOfException $exception) {
                var_dump( $exception->getMessages() );
            }
    }

}