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

	private $dummyBookingEntity;

	/**
	 * @var BookingManager
	 */
	private $bookingManager;


	private $dummyBookingSave;


	private $dummyFindBookings;


	private $dummyHolidayProvider;

	/**
	 * BookingManagerTest constructor.
	 */
    public function __construct()
    {
    	$this->setDummyBookingEntity();
//	    $this->setDummyBookingSave();
//	    $this->setDummyFindBookings();
//	    $this->getDummyHolidayProvider();


//        $this->bookingManager = new BookingManager(
//        	$this->getDummyBookingSave(),
//	        $this->getDummyFindBookings(new \DateTime()),
//	        $this->getDummyHolidayProvider(new \DateTime())
//        );
    }

    public function setDummyBookingEntity()
    {
	    $this->dummyBookingEntity = null;
	    $this->dummyBookingEntity = $this->createMock(BookingEntityInterface::class);
    }

    public function getDummyBookingSave()
    {
    	return $this->createMock(BookingSaveAndGetErrorsInterface::class);
    }

	public function getDummyFindBookings(\DateTime $date)
	{
		$inOneYear = clone $date;
		$inOneYear->add( new \DateInterval('P1Y'));

		var_dump($date);

		$bookingFind =  $this->createMock(FindBookingsInterface::class);
		$bookingFind->expects( $this->once() )
			->method('findAllFullBookingInPeriod')
			->with(
				$this->equalTo($date),
				$this->equalTo($inOneYear),
				bookingManager::MAX_NUMBER_OF_BOOKED_TICKETS
			)
			->will( $this->returnValue( array($date->format('Y').'-12-10', $date->format('Y').'-01-10', $date->format('Y').'-11-05')) )
		;

		return $bookingFind;
	}

	public function getDummyHolidayProvider(\DateTime $date)
	{
		$holidProvider = $this->createMock(HolidayProviderInterface::class);
		$holidProvider
			->expects($this->once())
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

//	    $this->setDummyObjectUsingTheDate($date);
	    $bookingManager = new BookingManager(
	    	$this->getDummyBookingSave(),
	    	$this->getDummyFindBookings($date),
	    	$this->getDummyHolidayProvider($date)
	    );

        $forbiddenDay =  $bookingManager->isForbiddenDate($date);

        $this->assertTrue($forbiddenDay);

	    $message = implode(", ", $bookingManager->getErrorMessages());

	    $this->assertContains("Le ".$date->format('d/m/Y')." est un jour férié !", $message);
    }
//
//    /**
//     * Test if the date is a forbidden date or authorized date
//     * Test forbidden date : tuesday
//     * Have to return true
//     */
//    public function testTuesdayForbiddenDay(){
//	    $nextTuesdayDate = new \DateTime('next tuesday');
//
//	    $this->setDummyObjectUsingTheDate($nextTuesdayDate);
//
//
//        $forbiddenDay =  $this->bookingManager->isForbiddenDate($nextTuesdayDate);
//
//        $this->assertTrue($forbiddenDay);
//
//	    $message = implode(", ",$this->bookingManager->getErrorMessages());
//
//	    $this->assertContains("Le musée est fermé le mardi et le dimanche !", $message);
//    }
//
//    /**
//     * Test if the date is a forbidden date or authorized date
//     * Test forbidden date : tuesday
//     * Have to return true
//     */
//    public function testSundayForbiddenDay(){
//	    $nextSundayDate = new \DateTime('next sunday');
//
//	    $this->setDummyObjectUsingTheDate($nextSundayDate);
//
//        $forbiddenDay =  $this->bookingManager->isForbiddenDate($nextSundayDate);
//
//        $this->assertTrue($forbiddenDay);
//
//	    $message = implode(", ",$this->bookingManager->getErrorMessages());
//
//	    $this->assertContains("Le musée est fermé le mardi et le dimanche !", $message);
//    }
//
//	public function testOverReservationDate(){
//		$date = new \DateTime('2016-12-10');
//
//		if($date < new \DateTime()){
//			$date->add(new \DateInterval("P1Y"));
//		}
//
//		$this->setDummyObjectUsingTheDate($date);
//
//		$forbiddenDay =  $this->bookingManager->isForbiddenDate($date);
//
//		$this->assertTrue($forbiddenDay);
//
//		$message = implode(", ",$this->bookingManager->getErrorMessages());
//
//		$this->assertContains("Désolé les réservations sont complètes pour le ".$date->format('d/m/Y')." !", $message);
//	}
//
//	/**
//	 * Test if the date inferior to current day
//	 * Test forbidden date : tuesday
//	 * Have to return true
//	 */
//	public function testInferiorDate(){
//		$inferiorDate = new \DateTime('2016-09-01');
//
//		$this->setDummyObjectUsingTheDate($inferiorDate);
//
//
//		$forbiddenDay =  $this->bookingManager->isForbiddenDate($inferiorDate);
//
//		$this->assertTrue($forbiddenDay);
//
//		$message = implode(", ",$this->bookingManager->getErrorMessages());
//
//		$this->assertContains("Vous ne pouvez pas réserver une date inférieur à la date du jour !", $message);
//	}
//
//    /**
//     * Test if the date is a forbidden date or authorized date
//     * Test authorized date
//     * Have to return false
//     */
//    public function testGoodBookingDate(){
//        $date = new \DateTime('09 September');
//
//	    if($date < new \DateTime()){
//		    $date->add(new \DateInterval("P1Y"));
//	    }
//
//	    $this->setDummyObjectUsingTheDate($date);
//
//        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);
//
//        $this->assertFalse($forbiddenDay);
//    }
//
//
//
//    public function testGetNextGoodDate(){
//    	$date = new \DateTime('next Tuesday');
//
//		$this->setDummyObjectUsingTheDate($date);
//
//	    $goodDate =  $this->bookingManager->getNextGoodDate($date);
//
//	    $this->assertEquals(3, $goodDate->format('w'));//Have to return 3 for Wednesday day of week
//    }
//
//	/**
//	 * @expectedException \Exception
//	 * @expectedExceptionMessage You must use a entity who implement the AppBundle\Entity\BookingEntityInterface !
//	 */
//    public function testNotBookingEntityInterfaceSendOnGetCurrentBooking(){
//    	$bookingSave = $this->createMock(BookingSaveInterface::class);
//	    $holidayProvider = $this->createMock(HolidayProviderInterface::class);
//
//
//		$findBooking = $this->createMock(FindBookingsInterface::class);
//	    $findBooking->expects($this->once())
//		    ->method('getCurrentBooking')
//		    ->will($this->returnValue(new \stdClass()));
//
//	    $bookingManager = new BookingManager($bookingSave, $findBooking, $holidayProvider);
//
//	    $bookingManager->getCurrentBooking();
//    }
//
//    protected function setDummyObjectUsingTheDate(\DateTime $date)
//    {
//    	$inOneYear = clone $date;
//	    $inOneYear->add( new \DateInterval('P1Y'));
//
//	    $this->dummyHolidayProvider
//		    ->expects($this->once())
//		    ->method('getHolidayDatesFor')
//		    ->with($this->equalTo( $date->format('Y') ))
//		    ->will( $this->returnValue( array($date->format('Y').'-12-25', $date->format('Y').'-10-01', $date->format('Y').'-05-01')) )
//	    ;
//
//	    $this->dummyFindBookings
//		    ->expects( $this->once() )
//		    ->method('findAllFullBookingInPeriod')
//		    ->with(
//			    $this->equalTo($date),
//			    $this->equalTo($inOneYear),
//			    bookingManager::MAX_NUMBER_OF_BOOKED_TICKETS
//		    )
//		    ->will( $this->returnValue( array($date->format('Y').'-12-10', $date->format('Y').'-01-10', $date->format('Y').'-11-05')) )
//	    ;
//    }
}