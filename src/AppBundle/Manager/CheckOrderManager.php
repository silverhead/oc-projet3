<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 14:16
 */

namespace AppBundle\Manager;

use AppBundle\Bridge\BridgeBookingORMInterface;
use AppBundle\Entity\Order;
use AppBundle\Helper\CanHaveErrorsInterface;
use AppBundle\Service\FindBookingsInterface;
use AppBundle\Bridge\BridgeOrderORMInterface;

class CheckOrderManager implements CanHaveErrorsInterface
{

    /**
     * @var FindBookingsInterface
     */
    private $bridgeBooking;

    /**
     * @var BridgeOrderORMInterface
     */
    private $bridgeOrder;

    /**
     * @var array
     */
    private $errors;

    public function __construct
    (
        BridgeBookingORMInterface $bridgeBookingORM,
        BridgeOrderORMInterface $bridgeOrder
    )
    {
        $this->bridgeBooking = $bridgeBookingORM;
        $this->bridgeOrder = $bridgeOrder;
    }

    public function getCurrentBooking()
    {
        return $this->bridgeBooking->getCurrentBooking();
    }

    public function getCurrentOrder()
    {
        $order = $this->bridgeOrder->getCurrent();

	    $this->bridgeOrder->deleteLines($order);

	    return $order;
    }


    public function saveOrder(Order $order)
    {
	    $booking = $this->bridgeBooking->getCurrentBooking();

        if(!$this->bridgeOrder->save($order, $booking)){
            $this->errors = $this->bridgeOrder->getErrors();
            return false;
        }

        return true;
    }

    public function getAutoPromo()
    {
        return $this->bridgeBooking->getAutoPromo();
    }

    /**
     * @return array of error messages if exists
     */
    public function getErrors()
    {
      return $this->errors;
    }
}