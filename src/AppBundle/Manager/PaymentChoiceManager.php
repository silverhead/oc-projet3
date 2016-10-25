<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 25/10/16
 * Time: 14:49
 */

namespace AppBundle\Manager;


use AppBundle\Bridge\BridgeOrderORMInterface;
use AppBundle\Bridge\PaymentCoreBridgeInterface;

class PaymentChoiceManager
{
	/**
	 * @var PaymentCoreBridgeInterface
	 */
	private $paymentCoreBridge;

	/**
	 * @var BridgeOrderORMInterface
	 */
	private $orderORMBridge;


	public function __construct(PaymentCoreBridgeInterface $paymentCoreBridge, BridgeOrderORMInterface $orderORMBridge)
	{
		$this->paymentCoreBridge = $paymentCoreBridge;
		$this->orderORMBridge = $orderORMBridge;
	}

	public function getListOfPaymentChoices()
	{
		return $this->paymentCoreBridge->getListOfAvailablePayments();
	}

	public function getCurrentOrder()
	{
		return $this->orderORMBridge->getCurrent();
	}

	public function setPaymentInstruction($data)
	{
		$this->orderORMBridge->setPaymentInstruction(
			$this->paymentCoreBridge->getPaymentInstruction($data)
		);
	}
}