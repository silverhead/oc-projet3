<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 01/10/16
 * Time: 11:22
 */

namespace AppBundle\units\Form\Type;

use AppBundle\Form\Type\BookingType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 *
 * Class BookingTypeTest
 *
 * @package AppBundle\units\Form\Type
 */
class BookingTypeTest extends TypeTestCase
{
	public function testGoodServiceNaming()
	{
		$bookingType = new BookingType();

		$serviceName = $bookingType->getName();

		$this->assertEquals('app_bundle_booking_type', $serviceName);
	}
}