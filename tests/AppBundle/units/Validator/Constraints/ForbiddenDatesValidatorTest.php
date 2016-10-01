<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 01/10/16
 * Time: 11:04
 */

namespace AppBundle\units\Validator\Constraints;

use AppBundle\Manager\BookingManager;
use AppBundle\Validator\Constraints\ForbiddenDates;
use AppBundle\Validator\Constraints\ForbiddenDatesValidator;

/**
 * Class ForbiddenDatesValidator
 *
 * @package AppBundle\units\Validator\Constraints
 */
class ForbiddenDatesValidatorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException   \Exception
	 * @expectedExceptionMessage  The value must be a instance of \DateTime
	 */
	public function testBadValueParam()
	{

		$bookingManager = $this->createMock(BookingManager::class);


		$constraint = $this->createMock(ForbiddenDates::class);//Not really use on the method

		$badValue = new \stdClass();

		$validator = new ForbiddenDatesValidator($bookingManager);

		$validator->validate($badValue, $constraint); //the method require a \DateTime value
	}
}