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
     * @var TicketType
     */
    private $ticketType;

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
        $this->ticketType = $this->em->getRepository("AppBundle:TicketType")->findAll()[0];
    }

    public function addFakeBooking(){
        $booking = new Booking();

        $booking
            ->setBookingDate(new \DateTime())
            ->setTicketType($this->ticketType)
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
}
