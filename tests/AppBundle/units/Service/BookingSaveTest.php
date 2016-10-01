<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 01/10/16
 * Time: 10:42
 */

namespace AppBundle\units\Service;


use AppBundle\Entity\Booking;
use AppBundle\Service\BookingSave;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class BookingSaveTest extends KernelTestCase
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/***
	 * @var SessionInterface
	 */
	private $session;

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

	public function testSaveThrowException()
	{
		$bookingSave = new BookingSave($this->em, $this->session);

		$bookingEntity = new Booking();//stay empty for obtains a error

		$bookingSave->save($bookingEntity);

		$errorMessagetest = "Une erreur est intervenue lors de l'enregistrement dans la base ! Si le problème persiste ".
						"veuillez contacter l'administrateur du site";

		$errosMessageReturned = implode(", ", $bookingSave->getErrors());

		$this->assertContains($errorMessagetest, $errosMessageReturned);
	}
}