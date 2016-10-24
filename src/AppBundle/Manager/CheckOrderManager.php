<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 24/10/16
 * Time: 14:16
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Order;
use AppBundle\Helper\CanHaveErrors;
use AppBundle\Service\FindBookingsInterface;
use AppBundle\Bridge\BridgeOrderORMInterface;

class CheckOrderManager implements CanHaveErrors
{

    /**
     * @var FindBookingsInterface
     */
    private $findBookings;

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
        FindBookingsInterface $findBookings,
        BridgeOrderORMInterface $bridgeOrder
    )
    {
        $this->findBookings = $findBookings;
        $this->bridgeOrder = $bridgeOrder;
    }

    public function getCurrentBooking()
    {
        return $this->findBookings->getCurrentBooking();
    }

    public function getCurrentOrder()
    {
        return $this->bridgeOrder->getCurrent();
    }


    public function saveOrder(Order $order)
    {
        if(!$this->bridgeOrder->save($order)){
            $this->errors = $this->bridgeOrder->getErrors();
            return false;
        }

        return true;
    }

    /**
     * @return array of error messages if exists
     */
    public function getErrors()
    {
      return $this->errors;
    }
}