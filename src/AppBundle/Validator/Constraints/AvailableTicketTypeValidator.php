<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 07/10/16
 * Time: 06:36
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Entity\TicketType;
use AppBundle\Manager\BookingManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AvailableTicketTypeValidator
 *
 * Check if the ticket type selected is authorized to be used in function to day hour
 *
 * @package AppBundle\Validator\Constraints
 */
class AvailableTicketTypeValidator extends ConstraintValidator
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

		if(!($value instanceof TicketType)){
			throw new EntityNotFoundException("The field value have to implement a BookingEntityInterface!");
		}

		$selectedDate = $this->context->getRoot()->get($constraint->getFieldDate())->getData();

		$ticketTypesAvailable = $this->bookingManager->getTicketTypeAvailableFor($selectedDate);

		$ticketIds = array_map( function($ticketType){
			return $ticketType->getId();
		}, $ticketTypesAvailable);

		if(!in_array($value->getId(), $ticketIds)){
			$this->context
				->buildViolation($constraint->getMessage())
				->addViolation();
		}
	}
}