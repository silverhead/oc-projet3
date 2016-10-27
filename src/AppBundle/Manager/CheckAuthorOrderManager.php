<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 27/10/16
 * Time: 16:07
 */

namespace AppBundle\Manager;


use AppBundle\Bridge\BridgeOrderORMInterface;
use AppBundle\Entity\Order;

class CheckAuthorOrderManager
{

	/**
	 * @var Order
	 */
	private $order;

	public function __construct(BridgeOrderORMInterface $bridgeOrder)
	{
		$this->order = $bridgeOrder->getCurrent();
	}

	public function checkEmail()
	{

	}


}