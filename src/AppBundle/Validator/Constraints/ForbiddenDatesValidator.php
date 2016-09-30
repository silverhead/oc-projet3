<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 24/09/2016
 *
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Manager\BookingManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ForbiddenDatesValidator extends ConstraintValidator
{
	/**
	 * @var BookingManagerInterface
	 */
	private $bookingManager;

	public function __construct(BookingManagerInterface $bookingManager)
	{
		$this->bookingManager = $bookingManager;
	}


	public function validate($value, Constraint $constraint)
    {
        if( !($value instanceof \DateTime)){
            throw new \Exception("The value must be a instance of \DateTime");
        }

        if($this->bookingManager->isForbiddenDate($value)){

        	$message =  implode(", ", $this->bookingManager->getErrorMessages());

	        $this->context->buildViolation($message)
		        ->setParameter("%date%", $value->format('Y-m-d'))
		        ->addViolation();
        }
    }
}