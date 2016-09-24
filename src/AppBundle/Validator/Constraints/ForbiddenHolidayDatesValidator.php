<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 24/09/2016
 *
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Yasumi\Yasumi;

class ForbiddenHolidayDatesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if( !($value instanceof \DateTime)){
            throw new \Exception("The value must be a instance of DateTime");
        }

        $holidayProvider = Yasumi::create('France', $value->format('Y'));

        if($holidayProvider->isHoliday($value)){
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter("%date%", $value->format('Y-m-d'))
                ->addViolation();
        }
    }
}