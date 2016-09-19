<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 15/09/2016
 *
 */

namespace tests\AppBundle\units\Form\Model;

use AppBundle\Form\Model\Reservation;

class ReservationTest extends  \PHPUnit_Framework_TestCase
{
    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : chrismas day
     * Have to return true
     */
    public function testHolidayDate(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '01-05-2017');
        $reservation->setReservationDate($date);

        $forbiddenDay = $reservation->isHolidaysDate();

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testTuesdayForbiddenDay(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '20-09-2016');//This a Tuesday

        $reservation->setReservationDate($date);
        $forbiddenDay = $reservation->isNotReservationWeekDayDate();

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test forbidden date : tuesday
     * Have to return true
     */
    public function testSundayForbiddenDay(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '11-09-2016');//This a Sunday

        $reservation->setReservationDate($date);

        $forbiddenDay = $reservation->isNotReservationWeekDayDate();

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test authorized date
     * Have to return false
     */
    public function testNotHolidayDate(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '05-12-2016');

        $reservation->setReservationDate($date);

        $forbiddenDay = $reservation->isHolidaysDate();

        $this->assertFalse($forbiddenDay);
    }

    public function testOverReservationDate(){
    }
}