<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 28/09/2016
 *
 */

namespace tests\AppBundle\units\Manager;

use AppBundle\Entity\BookingEntityInterface;
use AppBundle\Manager\BookingManager;
use AppBundle\Service\BookingSaveAndGetErrorsInterface;
use AppBundle\Service\FindBookingsInterface;
use AppBundle\Service\HolidayProviderInterface;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraints\DateTime;

class DummyBookingSaveAndGetErrors implements BookingSaveAndGetErrorsInterface
{
    public function save(BookingEntityInterface $booking)
    {
       return true;
    }

    public function getErrors()
    {
       return array();
    }
}

/**
 * Class DummyFindBooking
 * @package tests\AppBundle\units\Manager
 */
class DummyFindBooking implements FindBookingsInterface
{
    public function find($id)
    {
        return null;
    }

    public function findAllFullBookingInPeriod(
    	\DateTime $start,
	    \DateTime $end,
	    $maxNumberOfBookedTickets)
    {
        return array('2016-12-10', '2017-01-10', '2016-11-05');
    }
}

class DummyHolidayProvider implements HolidayProviderInterface
{
    public function getHolidayDatesFor($year)
    {
        return array($year.'-12-25', $year.'-10-01', $year.'-05-01');//Juste for test
    }

}


/**
 * Class BookingManagerTest
 * @package tests\AppBundle\units\Manager
 */
class BookingManagerTest extends \PHPUnit_Framework_TestCase
{
    private $bookingManager;

    public function __construct()
    {
        $this->bookingManager = new BookingManager(new DummyBookingSaveAndGetErrors(), new DummyFindBooking(), new DummyHolidayProvider());
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Have to return true
     */
    public function testHolidayDate(){
        $date = new \DateTime('01 May');

	    if($date < new \DateTime()){
		    $date->add(new \DateInterval("P1Y"));
	    }


        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ",$this->bookingManager->getErrorMessages());

	    $this->assertContains("Le ".$date->format('d/m/Y')." est un jour férié !", $message);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testTuesdayForbiddenDay(){
	    $nextTuesdayDate = new \DateTime('next tuesday');

        $forbiddenDay =  $this->bookingManager->isForbiddenDate($nextTuesdayDate);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ",$this->bookingManager->getErrorMessages());

	    $this->assertContains("Le musée est fermé le mardi et le dimanche !", $message);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testSundayForbiddenDay(){
	    $nextSundayDate = new \DateTime('next sunday');

        $forbiddenDay =  $this->bookingManager->isForbiddenDate($nextSundayDate);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ",$this->bookingManager->getErrorMessages());

	    $this->assertContains("Le musée est fermé le mardi et le dimanche !", $message);
    }

	public function testOverReservationDate(){
		$date = new \DateTime('2016-12-10');

		if($date < new \DateTime()){
			$date->add(new \DateInterval("P1Y"));
		}

		$forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

		$this->assertTrue($forbiddenDay);

		$message = implode(", ",$this->bookingManager->getErrorMessages());

		$this->assertContains("Désolé les réservations sont complètes pour le ".$date->format('d/m/Y')." !", $message);
	}

	/**
	 * Test if the date inferior to current day
	 * Test forbidden date : tuesday
	 * Have to return true
	 */
	public function testInferiorDate(){
		$nextSundayDate = new \DateTime('2016-09-01');

		$forbiddenDay =  $this->bookingManager->isForbiddenDate($nextSundayDate);

		$this->assertTrue($forbiddenDay);

		$message = implode(", ",$this->bookingManager->getErrorMessages());

		$this->assertContains("Vous ne pouvez pas réserver une date inférieur à la date du jour !", $message);
	}

    /**
     * Test if the date is a forbidden date or authorized date
     * Test authorized date
     * Have to return false
     */
    public function testNotHolidayDate(){
        $date = new \DateTime('09 September');

	    if($date < new \DateTime()){
		    $date->add(new \DateInterval("P1Y"));
	    }

        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

        $this->assertFalse($forbiddenDay);
    }



    public function testGetNextGoodDate(){
    	$date = new \DateTime('next Tuesday');
	    $goodDate =  $this->bookingManager->getNextGoodDate($date);

	    $this->assertEquals(3, $goodDate->format('w'));//Have to return 3 for Wednesday day of week
    }

    public function testUpdateData(){
//    	$bookingEnityFake = $this->getMockBuilder(BookingEntityInterface::class)
//	        ->setMethods(['getBookingDate'])
//	        ->will($this->returnValue(new \DateTime()));
    }
}