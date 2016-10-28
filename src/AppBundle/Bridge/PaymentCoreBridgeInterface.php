<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 25/10/16
 * Time: 15:03
 */

namespace AppBundle\Bridge;


interface PaymentCoreBridgeInterface
{
	public function getListOfAvailablePayments();

	public function getPaymentInstruction($data);
}