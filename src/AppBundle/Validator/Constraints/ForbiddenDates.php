<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 24/09/2016
 *
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;


/**
 * Class ForbiddenDates
 * @package AppBundle\Validator\Constraints
 *
 */
class ForbiddenDates extends Constraint
{
    public $message = "The date %date% can't be booked !";

    public $forbiddenDates = [];

    public function __construct($options)
    {
        parent::__construct($options);

        $this->forbiddenDates = $options['forbiddenDates'];

        if(isset($options['message'])){
            $this->message = $options['message'];
        }
    }

    public function getForbiddenDates(){
        return $this->forbiddenDates;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getRequiredOptions()
    {
        return array(
            'forbiddenDates',
//            'message'
        );
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
