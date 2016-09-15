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
    public function testForbiddenDay(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '25-12-2016');

        $forbiddenDay = $reservation->isForbiddenDay($date);

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

        $forbiddenDay = $reservation->isForbiddenDay($date);

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

        $forbiddenDay = $reservation->isForbiddenDay($date);

        $this->assertTrue($forbiddenDay);
    }

    /**
     * Test if the date is a forbidden date or authorized date
     * Test authorized date
     * Have to return false
     */
    public function testNotForbiddenDay(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '05-12-2016');

        $forbiddenDay = $reservation->isForbiddenDay($date);

        $this->assertFalse($forbiddenDay);
    }

    public function testOverReservationDate(){
        $reservation = new Reservation();

        $date = \DateTime::createFromFormat('d-m-Y', '05-12-2016');

        $forbiddenDay = $reservation->isOverReservationDay();

        $this->assertFalse($forbiddenDay);
    }
}