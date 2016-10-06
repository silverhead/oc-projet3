<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 05/10/2016
 *
 */

namespace tests\AppBundle\units\Service;

use AppBundle\Entity\Booking;
use AppBundle\Entity\TicketType;
use AppBundle\Service\FindBooking;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class FindBookingTest
 * @package tests\AppBundle\units\Service
 */
class FindBookingTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /***
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array of TicketType
     */
    private $ticketTypes;

	/**
	 * @var TicketType
	 */
	private $ticketTypeFullDay;

	/**
	 * @var TicketType
	 */
	private $ticketTypeHalfDay;

    /**
     * @var Booking
     */
    private $bookingTest;

    protected function setUp()
    {
        self::bootKernel();

        $kernel = static::$kernel;

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->session = $kernel->getContainer()
            ->get('session');

        $this->em->beginTransaction();

        $this->setFakeTicketType();

        $this->addFakeBooking();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }


    public function setFakeTicketType(){
	    $this->ticketTypes = $this->em->getRepository("AppBundle:TicketType")->findAll();
        $this->ticketTypeFullDay = $this->ticketTypes[0];
        $this->ticketTypeHalfDay = $this->ticketTypes[1];
    }

    public function addFakeBooking(){
        $booking = new Booking();

        $booking
            ->setBookingDate(new \DateTime())
            ->setTicketType($this->ticketTypeFullDay)
            ->setTicketQuantity(1)
        ;

        $this->em->persist($booking);
        $this->em->flush();

        $this->bookingTest = $booking;
    }


    /**
     * Test find method width existing $id
     */
    public function testFindExistingBooking(){
        $findBooking = new FindBooking($this->em, $this->session);

        $bookingResult = $findBooking->find($this->bookingTest->getId());

        $this->assertEquals($bookingResult, $this->bookingTest);
    }

    /**
     * Test find method get new Booking
     */
    public function testFindNewBooking(){
        $findBooking = new FindBooking($this->em, $this->session);

        $bookingResult = $findBooking->find();

        $this->assertEquals(new Booking(), $bookingResult);
    }

    /**
     * Test find method with not existing id
     * @expectedException Doctrine\ORM\EntityNotFoundException
     */
    public function testFindNotExistingBooking(){
        $this->bookingTest;

        $findBooking = new FindBooking($this->em, $this->session);

        $findBooking->find(1000);
    }

    /**
     * Test if the it find 2 Ticket types if the hour is before fourteen hour
     */
    public function testFindTicketTypeAvailableForDateUnderFourteen()
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i', '2016-10-05 09:00');
        $findBooking = new FindBooking($this->em, $this->session);

        $results = $findBooking->findTicketTypeAvailableFor($date);

        $this->assertEquals(2, count($results));
    }

    /**
     * Test if the it find 1 Ticket type if the hour is after fourteen hour
     */
    public function testFindTicketTypeAvailableForDateAfterFourteen()
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i', '2016-10-05 14:00');
        $findBooking = new FindBooking($this->em, $this->session);

        $results = $findBooking->findTicketTypeAvailableFor($date);

        $this->assertEquals(1, count($results));
    }

	/**
	 * Test the default booking amount for 3 tickets and full day ticket type
	 */
    public function testGetBookingAmountDefaultFullDayType()
    {
    	$sumToTest = 16 * 3; // 16 -> default amount and 3 for the ticket quantity
	    $findBooking = new FindBooking($this->em, $this->session);
	    $amount = $findBooking->getBookingAmount($this->ticketTypeFullDay->getId(), 3);

	    $this->assertEquals($sumToTest, $amount);
    }

	/**
	 * Test the default booking amount for 3 tickets and half day ticket type
	 */
	public function testGetBookingAmountDefaultHalfDayType()
	{
		//default ticket amount * ticket quantity * 50% for half day
		$sumToTest = 16 * 3 * (50 / 100);
		$findBooking = new FindBooking($this->em, $this->session);
		$amount = $findBooking->getBookingAmount($this->ticketTypeHalfDay->getId(), 3);

		$this->assertEquals($sumToTest, $amount);
	}

	/**
	 * Test the booking amount for person aged from 12 to 60 years old
	 */
	public function testGetBookingAmountAgeBetween12And60()
	{
		$today30YearAgo = new \DateTime();
		$today30YearAgo->sub(new \DateInterval("P30Y"));

		//adult ticket amount * ticket quantity
		$sumToTest = 16 * 3;
		$findBooking = new FindBooking($this->em, $this->session);
		$amount = $findBooking->getBookingAmount($this->ticketTypeFullDay->getId(), 3, $today30YearAgo);

		$this->assertEquals($sumToTest, $amount);
	}

	/**
	 *  Test the booking amount for person aged from 0 to 4 years old
	 */
	public function testGetBookingAmountAgeBetween0And4()
	{
		$todaySubOneYear = new \DateTime();
		$todaySubOneYear->sub(new \DateInterval("P1Y"));// baby with 1 year old

		//baby ticket amount * ticket quantity
		$sumToTest = 0 * 3;
		$findBooking = new FindBooking($this->em, $this->session);
		$amount = $findBooking->getBookingAmount($this->ticketTypeFullDay->getId(), 3, $todaySubOneYear);

		$this->assertEquals($sumToTest, $amount);
	}

	/**
	 *  Test the booking amount for person aged from 4 to 12 years old
	 */
	public function testGetBookingAmountAgeBetween4And12()
	{
		$todaySubFiveYears = new \DateTime();
		$todaySubFiveYears->sub(new \DateInterval("P6Y"));// children with 1 year old

		//baby ticket amount * ticket quantity
		$sumToTest = 8 * 3;
		$findBooking = new FindBooking($this->em, $this->session);
		$amount = $findBooking->getBookingAmount($this->ticketTypeFullDay->getId(), 3, $todaySubFiveYears);

		$this->assertEquals($sumToTest, $amount);
	}

	/**
	 *  Test the booking amount for person aged from 60 to 99 years old
	 */
	public function testGetBookingAmountAgeBetween60And99()
	{
		$todaySubsixtyFiveYears = new \DateTime();
		$todaySubsixtyFiveYears->sub(new \DateInterval("P65Y"));// children with 1 year old

		//baby ticket amount * ticket quantity
		$sumToTest = 12 * 3;
		$findBooking = new FindBooking($this->em, $this->session);
		$amount = $findBooking->getBookingAmount($this->ticketTypeFullDay->getId(), 3, $todaySubsixtyFiveYears);

		$this->assertEquals($sumToTest, $amount);
	}

}
