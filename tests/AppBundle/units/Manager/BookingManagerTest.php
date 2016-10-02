<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 28/09/2016
 *
 */

namespace tests\AppBundle\units\Manager;

use AppBundle\Manager\BookingManager;
use AppBundle\Service\BookingSaveAndGetErrorsInterface;
use AppBundle\Service\BookingSaveInterface;
use AppBundle\Service\FindBookingsInterface;
use AppBundle\Service\HolidayProviderInterface;

/**
 * Class BookingManagerTest
 *
 * @package tests\AppBundle\units\Manager
 */
class BookingManagerTest extends \PHPUnit_Framework_TestCase
{
    public function getDummyBookingSave()
    {
    	return $this->createMock(BookingSaveAndGetErrorsInterface::class);
    }

	public function getDummyFindBookings()
	{
        $date = new \DateTime();

		$bookingFind =  $this->createMock(FindBookingsInterface::class);
		$bookingFind->expects( $this->any() )
			->method('findAllFullBookingInPeriod')
			->will( $this->returnValue( array($date->format('Y').'-12-10', $date->format('Y').'-01-10', $date->format('Y').'-11-05')) )
		;

		return $bookingFind;
	}

	public function getDummyHolidayProvider(\DateTime $date)
	{
		$holidProvider = $this->createMock(HolidayProviderInterface::class);
		$holidProvider
			->expects($this->any())
			->method('getHolidayDatesFor')
			->with($this->equalTo( $date->format('Y') ))
			->will( $this->returnValue( array($date->format('Y').'-12-25', $date->format('Y').'-10-01', $date->format('Y').'-05-01')) )
		;

		return $holidProvider;
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

	    $bookingManager = new BookingManager(
	    	$this->getDummyBookingSave(),
	    	$this->getDummyFindBookings(),
	    	$this->getDummyHolidayProvider($date)
	    );

        $forbiddenDay =  $bookingManager->isForbiddenDate($date);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ", $bookingManager->getErrorMessages());

	    $this->assertContains("Le ".$date->format('d/m/Y')." est un jour férié !", $message);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testTuesdayForbiddenDay(){
	    $nextTuesdayDate = new \DateTime('next tuesday');

        $bookingManager = new BookingManager(
            $this->getDummyBookingSave(),
            $this->getDummyFindBookings(),
            $this->getDummyHolidayProvider($nextTuesdayDate)
        );

        $forbiddenDay =  $bookingManager->isForbiddenDate($nextTuesdayDate);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ", $bookingManager->getErrorMessages());

	    $this->assertContains("Le musée est fermé le mardi et le dimanche !", $message);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testSundayForbiddenDay(){
	    $nextSundayDate = new \DateTime('next sunday');

        $bookingManager = new BookingManager(
            $this->getDummyBookingSave(),
            $this->getDummyFindBookings(),
            $this->getDummyHolidayProvider($nextSundayDate)
        );

        $forbiddenDay =  $bookingManager->isForbiddenDate($nextSundayDate);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ", $bookingManager->getErrorMessages());

	    $this->assertContains("Le musée est fermé le mardi et le dimanche !", $message);
    }

	public function testOverReservationDate(){
		$date = new \DateTime('2016-12-10');

		if($date < new \DateTime()){
			$date->add(new \DateInterval("P1Y"));
		}

        $bookingManager = new BookingManager(
            $this->getDummyBookingSave(),
            $this->getDummyFindBookings(),
            $this->getDummyHolidayProvider($date)
        );

		$forbiddenDay =  $bookingManager->isForbiddenDate($date);

		$this->assertTrue($forbiddenDay);

		$message = implode(", ", $bookingManager->getErrorMessages());

		$this->assertContains("Désolé les réservations sont complètes pour le ".$date->format('d/m/Y')." !", $message);
	}

	/**
	 * Test if the date inferior to current day
	 * Test forbidden date : tuesday
	 * Have to return true
	 */
	public function testInferiorDate(){
		$inferiorDate = new \DateTime('2016-09-01');

        $bookingManager = new BookingManager(
            $this->getDummyBookingSave(),
            $this->getDummyFindBookings(),
            $this->getDummyHolidayProvider($inferiorDate)
        );

		$forbiddenDay = $bookingManager->isForbiddenDate($inferiorDate);

		$this->assertTrue($forbiddenDay);

		$message = implode(", ", $bookingManager->getErrorMessages());

		$this->assertContains("Vous ne pouvez pas réserver une date inférieur à la date du jour !", $message);
	}

    /**
     * Test if the date is a forbidden date or authorized date
     * Test authorized date
     * Have to return false
     */
    public function testGoodBookingDate(){
        $date = new \DateTime('09 September');

	    if($date < new \DateTime()){
		    $date->add(new \DateInterval("P1Y"));
	    }

        $bookingManager = new BookingManager(
            $this->getDummyBookingSave(),
            $this->getDummyFindBookings(),
            $this->getDummyHolidayProvider($date)
        );

        $forbiddenDay =  $bookingManager->isForbiddenDate($date);

        $this->assertFalse($forbiddenDay);
    }

    public function testGetNextGoodDate(){
    	$date = new \DateTime('next Tuesday');

        $bookingManager = new BookingManager(
            $this->getDummyBookingSave(),
            $this->getDummyFindBookings(),
            $this->getDummyHolidayProvider($date)
        );

	    $goodDate =  $bookingManager->getNextGoodDate($date);

	    $this->assertEquals(3, $goodDate->format('w'));//Have to return 3 for Wednesday day of week
    }


	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage You must use a entity who implement the AppBundle\Entity\BookingEntityInterface !
	 */
    public function testNotBookingEntityInterfaceSendOnGetCurrentBooking(){
    	$bookingSave = $this->createMock(BookingSaveInterface::class);
	    $holidayProvider = $this->createMock(HolidayProviderInterface::class);

		$findBooking = $this->createMock(FindBookingsInterface::class);
	    $findBooking->expects($this->once())
		    ->method('getCurrentBooking')
		    ->will($this->returnValue(new \stdClass()));

	    $bookingManager = new BookingManager($bookingSave, $findBooking, $holidayProvider);

	    $bookingManager->getCurrentBooking();
    }

    public function testGetTicketTypeAvailableFor()
    {
        $date = new \DateTime();

        $bookingSave = $this->createMock(BookingSaveInterface::class);
        $holidayProvider = $this->createMock(HolidayProviderInterface::class);

        $findBooking = $this->createMock(FindBookingsInterface::class);
        $findBooking->expects($this->once())
            ->method('findTicketTypeAvailableFor')
            ->with( $this->equalTo($date))
        ;

        $bookingManager = new BookingManager($bookingSave, $findBooking, $holidayProvider);
        $bookingManager->getTicketTypeAvailableFor($date);
    }
}