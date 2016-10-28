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
	 * @var BridgeOrderORMInterface
	 */
	private $bridgeOrder;

	/**
	 * @var array
	 */
	private $errors;

	public function __construct(BridgeOrderORMInterface $bridgeOrder)
	{
		$this->bridgeOrder = $bridgeOrder;
	}

	public function checkEmail($data)
	{
		if(false === $this->bridgeOrder->checkBelonging($data['email'])){
			$this->errors[] = "L'e-mail utilisé pour la commande ne correspond pas à l'e-mail envoyé !";

			return false;
		}

		return true;
	}

	public function getErrors()
	{
		return $this->errors;
	}

}