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

class DummyFindBooking implements FindBookingsInterface
{
    public function find($id)
    {
        return null;
    }

    public function findAllFullBookingInPeriod(\DateTime $start, \DateTime $end, $maxNumberOfBookedTickets)
    {
        return array();
    }
}

class DummyHolidayProvider implements HolidayProviderInterface
{
    public function getHolidayDatesFor($year)
    {
        return array($year.'-12-25', $year.'-10-01', $year.'-05-01');//Juste for test
    }

}



class BookingManagerTest extends \PHPUnit_Framework_TestCase
{
    private $bookingManager;

    public function __construct()
    {
        $this->bookingManager = new BookingManager(new DummyBookingSaveAndGetErrors(), new DummyFindBooking(), new DummyHolidayProvider());
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : chrismas day
     * Have to return true
     */
    public function testHolidayDate(){
        $date = \DateTime::createFromFormat('d-m-Y', '01-05-2016');
        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testTuesdayForbiddenDay(){
        $date = \DateTime::createFromFormat('d-m-Y', '20-09-2016');//This a Tuesday

        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testSundayForbiddenDay(){
        $date = \DateTime::createFromFormat('d-m-Y', '11-09-2016');//This a Sunday

        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test authorized date
     * Have to return false
     */
    public function testNotHolidayDate(){
        $date = \DateTime::createFromFormat('d-m-Y', '05-12-2016');

        $forbiddenDay =  $this->bookingManager->isForbiddenDate($date);

        $this->assertFalse($forbiddenDay);
    }

    public function testOverReservationDate(){
    }
}