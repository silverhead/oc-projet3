<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 14/10/16
 * Time: 16:31
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BirthdayValidator extends ConstraintValidator
{
    /**
     * Checks if the value is inferior at now or not, if it's superior then we notify to the user that must put an inferior date
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();

        if($value > $now){
            $this->context
                ->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }

}