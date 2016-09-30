<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 30/09/16
 * Time: 17:15
 */

namespace AppBundle\units\Entity;


use AppBundle\Entity\TicketType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

/**
 * Class TicketTypeTest
 * @package AppBundle\units\Entity
 */
class TicketTypeTest  extends KernelTestCase
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var Validation
	 */
	private $validator;

	/**
	 * @var TicketType
	 */
	private $ticketType;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp()
	{
		self::bootKernel();

		$kernel = static::$kernel;

		$this->validator = $kernel
			->getContainer()
			->get('validator.builder')
			->getValidator();

		$this->em = $kernel->getContainer()
			->get('doctrine')
			->getManager();

		$this->em->beginTransaction();
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

	/**
	 * TicketTypeTest constructor.
	 */
	public function __construct()
	{
		$this->ticketType = new TicketType();
	}

	public function testLabel(){
		$labelTest = "test";
		$this->ticketType->setLabel($labelTest);

		$labelCallback = $this->ticketType->getLabel();

		$this->assertEquals($labelTest, $labelCallback);

	}

	public function testEmptyValues(){
		$this->ticketType->setLabel('');
		$this->ticketType->setPercent('');

		$errors = $this->validator->validate($this->ticketType);

		$this->assertEquals(2, count($errors));
	}

	public function testBadValueForPercent(){
		$this->ticketType->setLabel('test');
		$this->ticketType->setPercent('test');

		$errors = $this->validator->validate($this->ticketType);

		$this->assertEquals(1, count($errors));
	}

	public function testGoodValues(){
		$this->ticketType->setLabel('test');
		$this->ticketType->setPercent(100);

		$errors = $this->validator->validate($this->ticketType);

		$this->assertEquals(0, count($errors));
	}

	public function testGetter(){
		$this->ticketType->setLabel('test');
		$this->ticketType->setPercent(100);

		$label   = $this->ticketType->getLabel();
		$percent = $this->ticketType->getPercent();

		$this->assertEquals('test', $label);
		$this->assertEquals(100, $percent);
	}

	public function testInsert()
	{
		$this->ticketType->setLabel('test');
		$this->ticketType->setPercent(100);

		$this->em->persist($this->ticketType);
		$this->em->flush();


		$this->assertGreaterThan(0, $this->ticketType->getId());
	}
}