<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 14/10/16
 * Time: 16:31
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class Birthday extends Constraint
{
    private $message = "Veuillez choisir une date inférieur à la date d'aujourd'hui !";

    public function getMessage(){
        return $this->message;
    }
}