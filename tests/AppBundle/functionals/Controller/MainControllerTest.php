<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 30/09/16
 * Time: 16:14
 */

namespace AppBundle\functionals\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MainControllerTest  extends WebTestCase
{
	const ROOTES = [
		'HOME' => '/',
		'AJAX_GET_TICKET_TYPE_LIST' => '/ajax/get/ticket_type_list_by_date.json',
		'USER_INFORMATIONS' => '/vos-coordonnees',
		'CHECK_ORDER' => '/verification-commande',
		'PAYMENT_CHOICE' => '/choix-paiement',
		'ORDER_CONFIRME' => '/confirmation-commande',
		'ORDER_CANCELED' => '/annulation-commande',
	];

	/**
	 * Page home
	 *
	 * check if the page exist and this title is good
	 */
	public function testIndex()
	{
		$client = static::createClient();

        $crawler = $client->request('GET', self::ROOTES['HOME']);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Choix des billets', $crawler->filter('h1')->text());
	}

	public function testTicketTypeListAction(){
        $client = static::createClient();

        $client->request('GET', self::ROOTES['AJAX_GET_TICKET_TYPE_LIST'], ['date' => (new \DateTime())->format('Y-m-d')] );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testTicketTypeListActionWithoutDateParameter(){
        $client = static::createClient();

        $client->request('GET', self::ROOTES['AJAX_GET_TICKET_TYPE_LIST']);


        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertContains('Ressource non trouvé', $client->getResponse()->getContent());
    }

	/**
	 * Page User informations / Ticket
	 *
	 * check if the page exist and this title is good
	 */
	public function testUserInformations()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', self::ROOTES['USER_INFORMATIONS']);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Bénéficiaires des billets', $crawler->filter('h1')->text());
	}

	/**
	 * Page check order
	 *
	 * check if the page exist and this title is good
	 */
	public function testCheckOrder()
	{
		$client = static::createClient();

		$crawler = $client->request('POST', self::ROOTES['CHECK_ORDER']);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Validation de la commande', $crawler->filter('h1')->text());
	}

	/**
	 * Page payment choice
	 *
	 * check if the page exist and this title is good
	 */
	public function testPaymentChoice()
	{
		$client = static::createClient();

		$crawler = $client->request('POST', self::ROOTES['PAYMENT_CHOICE']);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Choisir votre mode de paiement', $crawler->filter('h1')->text());
	}

	/**
	 * Page order confirme
	 *
	 * check if the page exist and this title is good
	 */
	public function testOrderConfirme()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', self::ROOTES['ORDER_CONFIRME']);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Validation paiement', $crawler->filter('h1')->text());
	}

	/**
	 * Page order cancel
	 *
	 * check if the page exist and this title is good
	 */
	public function testOrderCancel()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', self::ROOTES['ORDER_CANCELED']);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Paiement annulé', $crawler->filter('h1')->text());
	}
}