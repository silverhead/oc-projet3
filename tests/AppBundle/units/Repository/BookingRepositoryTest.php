<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 30/09/16
 * Time: 15:28
 */

namespace AppBundle\units\Repository;


use AppBundle\Entity\Booking;
use AppBundle\Entity\TicketType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookingRepositoryTest extends KernelTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var array of TicketType
	 */
	private $tiketTypes;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp()
	{
		self::bootKernel();

		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();

		$this->em->beginTransaction();
	}

	/**
	 * get first ticket type in the database (it's not important that is the first)
	 *
	 * @return TicketType
	 */
	public function getFakeTicketType()
	{
		if(null === $this->tiketTypes){
			$this->tiketTypes = $this->em->getRepository('AppBundle:TicketType')->findAll();
		}

		return $this->tiketTypes[0];
	}

	/**
	 * Create a fake booking
	 *
	 * @param \DateTime $date
	 * @param TicketType $ticketType
	 * @param $quantity
	 */
	public function setFakeData(\DateTime $date, TicketType $ticketType, $quantity)
	{
		$bookingEntity = new Booking();

		$bookingEntity
			->setBookingDate($date)
			->setTicketType($ticketType)
			->setTicketQuantity($quantity)
		;

		$this->em->persist($bookingEntity);
		$this->em->flush();
	}

	/**
	 * Create fakes booking for test testFindAllFullBookingInPeriod method
	 *
	 * @param \DateTime $date
	 */
	public function setFakeFullyDateDatas(\DateTime $date){
		$this->setFakeData($date, $this->getFakeTicketType(), 500);
		$this->setFakeData($date, $this->getFakeTicketType(), 200);
		$this->setFakeData($date, $this->getFakeTicketType(), 300);
	}

	/**
	 * Test findAllFullBookingInPeriod method of the repository
	 *
	 */
	public function testFindAllFullBookingInPeriodFullyDate()
	{
		$testDate = new \DateTime('2016-09-30');

		$this->setFakeFullyDateDatas($testDate);

		$startDate = $testDate;
		$endDate = $testDate;
		$maxNumberOfBookingTickets = 1000;

		$dates = $this->em
			->getRepository('AppBundle:Booking')
			->findAllFullBookingInPeriod($startDate, $endDate, $maxNumberOfBookingTickets)
		;

		$this->assertCount(1, $dates);
	}

	/**
	 * Test findAllFullBookingInPeriod method of the repository
	 *
	 */
	public function testFindAllFullBookingInPeriodNotFullyDate()
	{
		$testDate = new \DateTime('2016-09-31');

		$this->setFakeData($testDate, $this->getFakeTicketType(), 20);

		$startDate = $testDate;
		$endDate = $testDate;
		$maxNumberOfBookingTickets = 1000;

		$dates = $this->em
			->getRepository('AppBundle:Booking')
			->findAllFullBookingInPeriod($startDate, $endDate, $maxNumberOfBookingTickets)
		;



		$this->assertCount(0, $dates);
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
}