<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 30/09/16
 * Time: 16:40
 */

namespace AppBundle\functionals\Controller\index;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexPageTest extends WebTestCase
{
	const HOME = '/';

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

		$this->setFakeTicketType();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function tearDown()
	{
		parent::tearDown();

//		$this->em->rollBack();

		$this->em->close();
		$this->em = null; // avoid memory leaks
	}

	/**
	 * set tickets type
	 *
	 * @return TicketType
	 */
	public function setFakeTicketType()
	{
        $hour = (new \DateTime())->format('H');
		$this->tiketTypes = $this->em->getRepository('AppBundle:TicketType')->findTicketTypeAvailableFor($hour);
	}

	public function testAllFieldsError()
	{
		$client   = static::createClient();

		$crawler  = $client->request('GET', self::HOME);

		$form     = $crawler->filter("form")->form();

		$form->disableValidation();

		$form['booking[bookingDate]'] =  (new \DateTime('next Tuesday'))->format('Y-m-d');//make a Tuesday date for test if error
		$form['booking[ticketType]'] = 3;
		$form['booking[ticketQuantity]'] = '';

		$crawler = $client->submit($form);

		$bookingDate = $crawler->filter("#booking_bookingDate")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains("Le musée est fermé le mardi et le dimanche !", $bookingDate);

		$ticketType = $crawler->filter("#booking_ticketType")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains("Cette valeur n'est pas valide.", $ticketType);

		$ticketQuantity = $crawler->filter("#booking_ticketQuantity")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains("123456789", $ticketQuantity);
	}

	public function testAllFieldsOk()
	{
		$client   = static::createClient();

		$crawler  = $client->request('GET', self::HOME);

		$form     = $crawler->filter("form")->form();

		$object = (object) [
			'bookingDate' =>  (new \DateTime('next Monday'))->format('Y-m-d'),
			'ticketType' =>  $this->tiketTypes[0]->getId(),
			'ticketQuantity' =>  1,
		];

		$form['booking[bookingDate]'] = $object->bookingDate;
		$form['booking[ticketType]'] = $object->ticketType;
		$form['booking[ticketQuantity]']  = $object->ticketQuantity;

		$client->submit($form);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContains('Bénéficiaires des billets', $crawler->filter('h1')->text());

		$crawlerNext  = $client->request('GET', self::HOME);

		$form     = $crawlerNext->filter("form")->form();

		$this->assertEquals($form['booking[bookingDate]']->getValue(), $object->bookingDate);
		$this->assertEquals($form['booking[ticketType]']->getValue(), $object->ticketType);
		$this->assertEquals($form['booking[ticketQuantity]']->getValue(), $object->ticketQuantity);
	}
}