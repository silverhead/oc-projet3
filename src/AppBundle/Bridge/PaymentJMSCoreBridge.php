<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 25/10/16
 * Time: 15:05
 */

namespace AppBundle\Bridge;


class PaymentJMSCoreBridge implements  PaymentCoreBridgeInterface
{

	private $paymentPluginCtrl;

	public function __construct($paymentPluginCtrl)
	{
		$this->paymentPluginCtrl = $paymentPluginCtrl;
	}

	public function getListOfAvailablePayments()
	{
		return [
			'paypal_express_checkout' => [
				'return_url' => 'https://example.com/return-url',
				'cancel_url' => 'https://example.com/cancel-url',
				'useraction' => 'commit',
			],
		];
	}

	public function getPaymentInstruction($data)
	{
		$this->paymentPluginCtrl->createPaymentInstruction(
			$instruction = $data
		);

		return $instruction;
	}
}