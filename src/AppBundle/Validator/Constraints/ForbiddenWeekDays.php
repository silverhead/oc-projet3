<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 24/09/2016
 *
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class ForbiddenWeekDaysConstraint
 * @package AppBundle\Validator\Constraint
 *
 * This class allows to ban certain day of the week
 *
 * @Annotation
 */
class ForbiddenWeekDays extends Constraint
{

    public $forbiddenWeekDays = [];

    public $message = "The date %date% is not booking day !";

    /**
     * ForbiddenWeekDays constructor.
     * @param mixed|null $options
     */
    public function __construct($options)
    {
        parent::__construct($options);

        $this->forbiddenWeekDays = $options['forbiddenWeekDays'];

        if(isset($options['message'])){
            $this->message = $options['message'];
        }
    }

    public function getForbiddenWeekDays(){
        return $this->forbiddenWeekDays;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getRequiredOptions()
    {
        return array(
            'forbiddenWeekDays',
//            'message'
        );
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
