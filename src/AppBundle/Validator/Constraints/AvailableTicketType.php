<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 07/10/16
 * Time: 06:35
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class AvailableTicketType
 *
 * Constraint for test if the selected ticket type is available for the date and hour (if the date is today)
 *
 * @package AppBundle\Validator\Constraints
 */
class AvailableTicketType extends Constraint
{
	public $message = "Le type de ticket n'est pas disponible pour la date sélectionnée !";
	public $fieldDate;

	public function getFieldDate()
	{
		return $this->fieldDate;
	}

	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefaultOption()
	{
		return 'fieldDate';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRequiredOptions()
	{
		return array('fieldDate');
	}
}