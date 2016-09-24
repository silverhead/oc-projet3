<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 24/09/2016
 *
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ForbiddenDatesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if( !($value instanceof \DateTime)){
            throw new \Exception("The value must be a instance of \DateTime");
        }

        if(in_array($value->format('Y-m-d'), $constraint->getForbiddenDates())){
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter("%date%", $value->format('Y-m-d'))
                ->addViolation();
        }
    }
}