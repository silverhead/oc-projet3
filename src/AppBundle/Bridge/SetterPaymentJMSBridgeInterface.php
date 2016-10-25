<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 25/10/16
 * Time: 15:28
 */

namespace AppBundle\Bridge;


use JMS\Payment\CoreBundle\Model\PaymentInstructionInterface;

interface SetterPaymentJMSBridgeInterface
{
	public function setPaymentInstruction(PaymentInstructionInterface $instruction);
}