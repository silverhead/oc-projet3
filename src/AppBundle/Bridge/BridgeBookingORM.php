<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 28/10/16
 * Time: 13:57
 */

namespace AppBundle\Bridge;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BridgeBookingORM
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * @var SessionInterface
	 */
	private $session;

	public function __construct(EntityManagerInterface $em, SessionInterface $session)
	{
		$this->em = $em;
		$this->session = $session;
	}

	public function removeCurrent(){
		$this->session->remove('booking');
	}
}