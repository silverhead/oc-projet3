<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 24/09/2016
 *
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ForbiddenHolidayDates
 * @package AppBundle\Validator\Constraints
 *
 */
class ForbiddenHolidayDates extends Constraint
{
    public $message = "The date %date% can't be booked !";

    public function __construct($options = null)
    {
        parent::__construct($options);

        if(isset($options['message'])){
            $this->message = $options['message'];
        }
    }

    public function getMessage()
    {
        return $this->message;
    }
}
